Vue.component('vue-collection', {
    props: ['value'],
    data() {
        return {     
            collections: [],
            edit_collection: {},
            dialog_edit: false,
            active_tab:1,
            site_base_url: CI.base_url,
            action_menu: false,        
            action_menu_x: 0,
            action_menu_y: 0,
            action_menu_id: 0
        }
    },
    
    created: function() {
        this.loadCollections();
    },
    computed: {
        Collections() {
            return this.collections;
        }
    },
    methods: {
        showMenu: function(data) {
            console.log("showMenu", data);
            let e=data.e;
            e.preventDefault()
            this.action_menu = false
            this.action_menu_id = data.id
            this.action_menu_x = e.clientX
            this.action_menu_y = e.clientY
            this.$nextTick(() => {
                this.action_menu = true
            })            
        },
        momentDate(date) {
            return moment.utc(date).format("MMM d, YYYY")
        },
        loadCollections: function() {
            vm = this;
            let url = CI.base_url + '/api/collections/tree';
            console.log("loading collections");
            return axios
                .get(url)
                .then(function(response) {
                    vm.collections = response.data.collections;
                    console.log("loading collections",vm.collections);
                })
                .catch(function(error) {
                    console.log("error", error);
                    alert("Failed to load collections: " +  vm.errorResponseMessage(error));
                });
        },
        findByCollectionId: function(id) {
            //recursively search for collection by id
            let collection = null;
            let search = function(items) {
                for (let i = 0; i < items.length; i++) {
                    if (items[i].id == id) {
                        collection = items[i];
                        break;
                    }
                    if (items[i].items) {
                        search(items[i].items);
                    }
                }
            }
            search(this.collections);
            return collection;
        },
        editCollectionById: function(id) {
            let collection = this.findByCollectionId(id);
            if (collection) {
                //this.edit_collection = JSON.parse(JSON.stringify(collection));
                this.edit_collection = {
                    id: collection.id,
                    title: collection.title,
                    description: collection.description,
                    pid: collection.pid
                }
                this.dialog_edit = true;
            }
        },
        addChildCollectionById: function(pid) {
            let collection = this.findByCollectionId(pid);
            if (collection) {
                this.edit_collection = {
                    pid: pid
                };
                this.dialog_edit = true;
            }
        },
        createCollection: function() {
            this.edit_collection = {};
            this.dialog_edit = true;
        },
        updateCollection: function(collection) {            
            let url = CI.base_url + '/api/collections';

            if (collection.id) {
                url = CI.base_url + '/api/collections/update/' + collection.id;
            }

            let form_data = collection;
            let vm=this;

            axios.post(url,
                    form_data
                )
                .then(function(response) {
                    console.log(response);
                    vm.loadCollections();
                    vm.dialog_edit = false;
                })
                .catch(function(error) {
                    console.log("error", error);
                    alert("Failed: " +  vm.errorResponseMessage(error));
                });
        },
        errorResponseMessage: function(error) {
            if (error.response.data.error) {
                return error.response.data.error;
            }

            if (error.response){
                return JSON.stringify(error.response.data);
            }

            return JSON.stringify(error);
        },
        DeleteCollection: function(id) {
            if (!confirm("Are you sure you want to delete the collection?")) {
                return false;
            }

            vm = this;
            let url = CI.base_url + '/api/collections/delete/' + id;

            axios.post(url)
                .then(function(response) {
                    vm.loadCollections();
                })
                .catch(function(error) {
                    console.log("error", error);
                    alert("Failed: " +  vm.errorResponseMessage(error));
                });
        },
        ManageCollectionAccess: function(id) {
            this.$router.push('/manage-users/' + id);
        }
    },
    template: `
    <div class="vue-collection-component">
        
            <vue-edit-collection v-model="dialog_edit" :collection="edit_collection" v-on:update-collection="updateCollection" vonremove-access="UnshareProjectWithUser"></vue-edit-collection>
            
            <section class="container">

                    <div class="row">

                        <div class="projects col " >

                            <h3 class="mt-3 mb-1">Collections</h3>

                            <div class="d-flex">                            

                                <v-tabs background-color="transparent" v-model="active_tab">
                                    <v-tab><v-icon>mdi-text-box</v-icon> <a :href="site_base_url + '/editor'">{{$t("projects")}}</a></v-tab>
                                    <v-tab active><v-icon>mdi-folder-text</v-icon> <a :href="site_base_url + '/collections'">{{$t("collections")}}</a> </v-tab>
                                    <!--<v-tab>Archives</v-tab>-->
                                    <v-tab><v-icon>mdi-alpha-t-box</v-icon> <a :href="site_base_url + '/templates'">{{$t("templates")}}</a></v-tab>
                                </v-tabs>


                                <div class="justify-content-end">
                                    <v-btn color="primary"  @click="createCollection">Create new collection</v-btn>
                                </div>
                            </div>

                            <div class="bg-light p-3 shadow mt-2" >
                                <div class="p-3 border text-center text-danger" v-if="!Collections || Collections.found<1"> No projects found!</div>

                                <div v-if="!Collections || Collections.found>0" class="row mb-2 border-bottom  mt-3">
                                    <div class="col-md-6">
                                        <div class="p-2" v-if="Collections">
                                            <strong>{{parseInt(collections.found)}}</strong> collections
                                        </div>
                                    </div>
                                </div>

                                <template v-if="Collections.length>0">
                                <div class="row border-bottom">
                                    <div class="col-1">
                                        #
                                    </div>
                                    <div class="col">
                                        <strong>Collection</strong>
                                    </div>
                                    <div class="col-1"><strong>Users</strong></div>
                                    <div class="col-1"><strong>Projects</strong></div>
                                    <div class="col-1"></div>
                                </div>


                                <div v-for="(collection,index) in Collections" >
                                    <vue-tree-list :value="collection" v-on:show-menu="showMenu"></vue-tree-list>
                                </div>
                                </template>

                            </div>

                        </div>

                    </div>
            </section>


            <template>
                <v-menu
                    v-model="action_menu"
                    :position-x="action_menu_x"
                    :position-y="action_menu_y"
                    absolute
                    offset-y
                >
                    <v-list>
                        <v-list-item>
                            <v-list-item-title @click="editCollectionById(action_menu_id)"><v-btn text>{{$t('Edit')}}</v-btn></v-list-item-title>
                        </v-list-item>
                        <v-list-item>    
                            <v-list-item-title @click="addChildCollectionById(action_menu_id)"><v-btn text>{{$t('Add sub-collection')}}</v-btn></v-list-item-title>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-title @click="ManageCollectionAccess(action_menu_id)"><v-btn text>{{$t('Manage access')}}</v-btn></v-list-item-title>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-title @click="DeleteCollection(action_menu_id)"><v-btn text>{{$t('Delete')}}</v-btn></v-list-item-title>
                        </v-list-item>          
                    </v-list>
                </v-menu>
            </template>



        </div>
                
    </div>
    `
});

