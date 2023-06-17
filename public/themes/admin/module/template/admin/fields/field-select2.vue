<template>
    <div>
        <v-select @open="openSearch" :multiple="isMultiple" :value="valueOption" @input="onSelect" label="text" :options="options" @search="fetchOptions">
            <template slot="no-options">
                Type to select
            </template>
        </v-select>
    </div>
</template>
<script>
    import vSelect from 'vue-select'
    import { abstractField } from "vue-form-generator";

    export default {
        mixins: [ abstractField ],
        data(){
            return {
                options:[],
                selectedText:'',
                valueOption:{
                    id:'',
                    text:''
                }
            }
        },
        computed:{
            isMultiple(){
                return this.schema.select2.multiple == "true"
            }
        },
        mounted: function () {
            if(this.value){
                var me = this;
                $.ajax({
                    url:this.schema.select2.ajax.url,
                    method:'get',
                    data:{
                        pre_selected:1,
                        selected:this.value
                    },
                    success:function(json){
                        if (me.isMultiple){
                            me.valueOption = json.items;
                        }else{
                            me.valueOption = json;

                        }
                    },
                    error:function(e){

                    }

                })

            }
        },
        watch: {
            value: function (value) {
                console.log('value change');
            },
            options: function (options) {
                // update options
                //$(this.$el).empty().select2({ data: options })
            }
        },
        destroyed: function () {
            //$(this.$el).off().select2('destroy')
        },
        components:{
            vSelect
        },
        methods:{
            onSelect:function (option){
                if (this.isMultiple){
                    let array = option.map(function(val){
                        return val.id;
                    })
                    this.value=array
                    this.valueOption = option;
                }else{
                    this.value = option.id;
                    this.valueOption = option;
                }

            },
            fetchOptions:function (search, loading){
                const me = this;
                loading(true);
                $.ajax({
                    url:this.schema.select2.ajax.url,
                    method:'get',
                    data:{
                        q:search
                    },
                    success:function(json){
                        loading(false);
                        me.options = json.results;
                    },
                    error:function(e){
                        loading(false);
                    }

                })
            },
            openSearch:function (e){
                const me = this;
                $.ajax({
                    url:this.schema.select2.ajax.url,
                    method:'get',
                    success:function(json){
                        me.options = json.results;
                    },
                    error:function(e){
                    }

                })
            }
        }
    };
</script>
