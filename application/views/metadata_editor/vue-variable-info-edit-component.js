///variable info edit form
Vue.component('variable-info', {
    props:['value'],
    data: function () {    
        return {   
            //variable:this.value,            
            variable_formats:{
                "numeric": "Numeric",
                "character": "String",
                "fixed": "Fixed string"
            },
            variable_intervals:{
                "contin": "Continuous",
                "discrete": "Discrete"
            },
            missing_template:[
                {
                    "key": "value",
                    "title": "Value",
                    "type": "text"
                }
            ],
        }
    },
    methods: {
        OnValueUpdate: function () {
          this.variable.update_required = true;
          this.$emit('input', this.variable);
        }
    },
    created: function(){
        
    },
    computed: {       
        variable:{
            get(){
                if (this.value){
                    if (!this.value.var_format){
                        this.value.var_format={
                            "type": ""
                        }                
                    }
                    if (!this.value.var_format.type){
                        this.value.var_format.type="";
                    }
                }
                return this.value;
            },
            set(newValue){
                this.$emit('input', newValue);                
            }
        }
    },
    template: `
        <div class="variable-categories-edit-component" style="height:100vh" v-if="variable">
            <!--var-format-->
            <div style="font-size:small;" class="mb-2" >
                <div class="section-title p-1 bg-variable"><strong>Variable information</strong></div>

                <div class="p-2" v-if="variable">

                <div class="form-group form-field switch-field" >
                    <v-switch
                    v-model="variable.var_wgt"
                    label="is weight variable?"
                    true-value="1"
                    false-value="0"
                    ></v-switch>                    
                </div>


                <div class="form-group form-field">
                    <label>Interval type</label>                     
                    <select 
                        v-model="variable.var_intrvl" 
                        class="form-control  form-control-sm form-field-dropdown"
                        id="variable_intervals" 
                    >
                        <option value="">Select</option>
                        <option v-for="(option_key,option_value) in variable_intervals" v-bind:value="option_value">
                            {{ option_key }}
                        </option>
                    </select>
                </div>
                
                <div class="form-group form-field">
                    <label>Format</label>
                    <select 
                        v-model="variable.var_format.type" 
                        class="form-control  form-control-sm form-field-dropdown"
                        id="var_format_type" 
                    >
                        <option value="">Select</option>
                        <option v-for="(option_key,option_value) in variable_formats" v-bind:value="option_value">
                            {{ option_key }}
                        </option>
                    </select>
                </div>

                <div class="form-group form-field">                                        
                    <div class="row no-gutters">
                        <div class="col">
                            <label>Min</label>
                            <input type="number" class="form-control form-control-sm form-control-xs" v-model="variable.var_valrng.range.min" />
                        </div>
                        <div class="col mr-1 ml-1">
                            <label>Max</label>
                            <input type="number" class="form-control form-control-sm form-control-xs" v-model="variable.var_valrng.range.max" />
                        </div>
                        <div class="col">
                            <label>Decimals</label>
                            <input type="number" class="form-control form-control-sm form-control-xs" v-model="variable.var_dcml" />
                        </div>
                    </div>    
                </div>

                <div class="form-group form-field" v-if="variable.var_invalrng">
                    <label>Missing</label>
                    
                        <repeated-field
                                @input="OnValueUpdate"  
                                v-model="variable.var_invalrng.values"
                                :field="missing_template"
                            >
                        </repeated-field>
                </div>

                </div>

            </div>
            <!--var-format-end-->
            
        </div>          
        `
});


