<template>
    <v-tabs background-color="transparent" class="mb-5">
        <v-tab  v-if="isField(prop.type)">Display</v-tab>
        <v-tab>Controlled vocabulary</v-tab>
        <v-tab>Default</v-tab>
        <v-tab v-if="isField(prop.type)">Validation rules</v-tab>

        <v-tab-item class="p-3"  v-if="isField(prop.type)">
            <!--display-->
            <div class="form-group">
                <label >Display:</label>
                <div class="text-secondary">UI control to use for editing field value</div>
                <select 
                    v-model="prop.type" 
                    class="form-control form-field-dropdown" >        
                    <option v-for="field_type in field_ui_types">
                        {{field_type}}
                    </option>
                </select>

                <div v-if="prop.type=='date'">
                    <pre>{{ActiveNode}}</pre>
                </div>
            </div>
            <!--end display -->
        </v-tab-item>

        <v-tab-item class="p-3">
            <!-- controlled vocab -->
            <template>
            <div class="form-group" >
                <label for="controlled_vocab">Controlled vocabulary:</label>
                <div class="border bg-white" style="max-height:300px;overflow:auto;">

                    <template v-if="isField(prop.type) || prop.type=='simple_array'">
                        <list-component @update:value="EnumListUpdate" :key="prop.key" v-model="prop.enum" class="border m-2 pb-2"/>
                    </template>
                    <template v-else>
                        <table-component @update:value="EnumListUpdate"  :key="prop.key"  v-model="prop.enum" :columns="prop.props" class="border m-2 pb-2" />
                    </template>
                </div>

            </div>
            </template>
            <!-- end controlled vocab -->
        </v-tab-item>
        <v-tab-item class="p-3">
            <!-- default -->
            <template v-if="prop.type!=='section_container' && prop.type!=='section'">
                <div class="form-group" >
                    <label for="controlled_vocab">Default:</label>
                    <div class="border bg-white" style="max-height:300px;overflow:auto;" v-if="prop.type=='array'">
                        <table-component @update:value="DefaultUpdate" v-model="prop.default" :columns="prop.props" class="border m-2 pb-2" />
                    </div>
                    <div class="border bg-white" v-else>
                        <div v-if="prop.type=='text' || prop.type=='dropdown' ">
                            <input class="form-control" type="text" v-model="prop.default"/>
                        </div>
                        <div v-else-if="prop.type=='textarea'">
                            <textarea class="form-control" style="height:200px;" v-model="prop.default"></textarea>
                        </div>
                    </div>
                </div>
            </template>
            <!-- end default -->
        </v-tab-item>
        <v-tab-item class="p-3" v-if="isField(prop.type)">
            <div class="form-group" >
                <label for="controlled_vocab">Validation rules:</label>
                <div class="bg-white border">
                    <validation-rules-component v-model="prop.rules" @update:value="RulesUpdate"  class="m-2 pb-2" />
                </div>
            </div>
        </v-tab-item>
    </v-tabs>

</template>