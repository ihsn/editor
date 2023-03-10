///variable categories edit form
Vue.component('variable-categories', {
    props:['value'],
    data: function () {    
        return {   
            //variable:this.value,
            catgry_columns:[
                {
                    "key": "value",
                    "title": "Value",
                    "type": "text"
                },
                {
                    "key": "labl",
                    "title": "Label",
                    "type": "text"
                }
            ],
            variable_formats:{
                "numeric": "Numeric",
                "fixed": "Fixed string"
            }
        }
    },
    created: function(){        
        //this.variable=this.value;
    },
    mounted: function () {
        //this.variable=this.value;
        /*if (!this.variable.var_catgry){
            this.variable.var_catgry=[{}];
            //this.field_data.push({});
        }*/
    },
    computed: {        
        variable:
        {
            get(){
                /*if (!this.value.var_catgry){
                    this.value.var_catgry=[{}];
                }*/
                return this.value;
            },
            set(val){
                this.$emit('update:value', val);
            }
        },
        variableCategories: function()
        {
            if (!this.variable.var_catgry){
                this.variable.var_catgry=[];
            }
            return this.variable
        }
    },
    /*methods: {
        updateValue: function () {
            console.log("emitting variable change",this.value);
          this.$emit('updateVariable', this.value);
        }
    },*/   
    template: `
        <div class="variable-categories-edit-component" style="height:inherit">
            <!--categories--> 
            <div style="font-size:small;" class="mb-2" >
                <div class="section-title p-1 bg-primary"><strong>Categories</strong></div>
                <div v-if="variable.var_intrvl=='discrete'">
                    <table-component v-model="variable.var_catgry" :columns="catgry_columns" class="border m-2 pb-2"/>
                </div>
                <div v-else class="m-3 border text-align-center p-3 text-secondary">Only available for Discrete variables</div>
            </div>
            
            <!--categories-end-->            
        </div>          
        `
});


