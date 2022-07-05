<div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <div>
            <div><strong>{{Title}}</strong></div>
            <div style="font-size:small;color:gray;">{{dataset_type}} - {{StudyIDNO}}</div>
        </div>
        <div>{{this.loading_status}}</div>

        <ul class="navbar-nav ml-auto">

            

            <li class="nav-item dropdown">
                
                <div class="mt-1 btn btn-primary btn-sm" data-toggle="dropdown" href="#">
                    <v-icon>mdi-alpha-t-box</v-icon> <i class="fas fa-angle-down"></i>
                </div>

                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-users mr-2"></i> Microdata Template
                        <span class="float-right text-muted text-sm">V1.0</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i> IHSN Template
                        <span class="float-right text-muted text-sm">1.0</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">View more</a>
                </div>                
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-wrench"></i>
                </a>
            </li>
        </ul>
    </nav>


    <aside class="main-sidebar sidebar-dark-primary elevation-4">

        <a href="#" class="brand-link">
            <i class="fas fa-compass"></i>
            <span class="brand-text font-weight-light">Metadata Editor</span>
        </a>        

        <div class="sidebar mt-3">

         <ul class="nav nav-pills nav-sidebar flex-column nav-collapse-hide-child" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item menu-is-opening menu-open">
                    <router-link class="nav-link" :to="'/'" >
                        <i class="nav-icon fas fa-copy"></i>
                        <p>Editor <i class="fas fa-angle-left right"></i></p>
                    </router-link>
                    <ul class="nav nav-treeview" style="display: block;">
                    <div style="font-size:small">
                    <v-treeview 
                        dark 
                        v-model="tree" 
                        @update:open="treeOnUpdate" 
                        :open.sync="initiallyOpen" 
                        :items="items" 
                        activatable dense 
                        item-key="key" 
                        item-text="title" 
                        open-on-click 
                        expand-icon="mdi-chevron-down"
                        indeterminate-icon="mdi-bookmark-minus"
                        on-icon="mdi-bookmark"
                        off-icon="mdi-bookmark-outline"
                        item-children="items">

                        <template #label="{ item }">
                            <span @click="treeClick(item)">{{item.title}}</span>
                        </template>

                        <template v-slot:prepend="{ item, open }">
                            <v-icon v-if="item.file" style="color:#949698">
                                {{ files[item.file] }}
                            </v-icon>    
                            <v-icon v-else-if="item.items" style="color:#949698">
                                {{ open ? 'mdi-folder-open' : 'mdi-folder' }}
                            </v-icon>
                            <v-icon v-else style="color:#949698">
                                {{ files['file'] }}
                            </v-icon>
                        </template>
                    </v-treeview>
                </div>
                    </ul>
                </li>

                <?php /*
                <li class="nav-item menu-is-opening menu-open">
                        <router-link class="nav-link" :to="'/external-resources/'" >
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            External resources
                            <i class="fas fa-angle-left right"></i>                            
                        </p>
                        </router-link>
                    <ul class="nav nav-treeview" style="display: block;font-size:small;margin-left:15px;">
                        <li v-for="(resource, index) in ExternalResources" class="nav-item">
                            <router-link class="nav-item" :to="'/external-resources/' + index"><i class="far fa-file-alt"></i> </i>{{resource.title}}</router-link>
                        </li> 

                    </ul>
                </li>
                */ ?>
                
            </ul>

        </div>

    </aside>

    <div class="content-wrapper">
        <section class="content">
            <!-- Provides the application the proper gutter -->
            <div class="container-fluid" style="overflow:auto;">
                <?php //route path: {{$route.fullPath}} ?>
                <div v-if="active_form_field">active_form_field.key:{{active_form_field.key}}</div>


                <div v-if="form_errors.length>0 || schema_errors.length>0" style="margin-bottom:15px;" class="pl-2">
                    <div style="color:red;font-weight:bold;">Please correct the following errors:</div>
                        <div style="color:red;" v-if="form_errors.length>0">
                            <div v-for="error in form_errors">
                                <span v-if="error.message">
                                    <i class="fas fa-times-circle"></i> {{ error.message }}
                                    <span class="label label-warning">{{error.property}}{{error.dataPath}}</span>
                                </span>
                                <span v-if="!error.message"><i class="fas fa-times-circle"></i> {{ error }}</span>
                            </div>
                        </div>
                        <div style="color:red;" v-if="schema_errors.length>0">
                            <div v-for="error in schema_errors">
                                <span v-if="error.message">
                                    <i class="fas fa-times-circle"></i> {{ error.message }}
                                    <span class="label label-warning">{{error.property}}{{error.dataPath}}</span>
                                </span>
                                <span v-if="!error.message"><i class="fas fa-times-circle"></i> {{ error }}</span>
                            </div>
                        </div>
                </div>


                <validation-observer ref="form" v-slot="{ invalid }">                

                <keep-alive include="datafiles">
                    <router-view :key="$route.fullPath" />
                </keep-alive>

                </validation-observer>
            </div>

           <?php // store_state:<pre>{{$store.state}}</pre> ?>
        </section>
    </div>

    <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <!-- Content of the sidebar goes here -->
      control side bar

<div>something</div>
<div>something</div>
<div>something</div>
<div>something</div>
<div>something</div>
<div>something</div>
<div>something</div>



    </div>
  </aside>
  <!-- /.control-sidebar -->




    <div id="sidebar-overlay"></div>
</div>