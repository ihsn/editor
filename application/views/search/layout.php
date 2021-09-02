<style>
 /* .breadcrumb{display:none;} */
 .search-nav-tabs .nav-item .active{   
    background-color: transparent;
    border:0px;
    border-bottom: 3px solid red;    
}

.dataset-type-label{
    text-transform: uppercase;
    color:gray;
    font-size:10px;
    border:1px solid gainsboro;
    padding:3px;
    font-weight:normal!important;
}

#catalog-search-form{
    margin-top:50px;
}

.search-box-container{
    position:relative;
}

.clear-search-button {
    position: absolute;
    right: 140px;
    top: 17px;
    z-index: 999;
    display: none;
}


.nav-tabs-auto-overflow {
  overflow-x: auto;
  overflow-y: hidden;
  display: -webkit-box;
  display: -moz-box;
}
.nav-tabs-auto-overflow >li {
  float: none;
}

.filters-container{
    width:100%;
}

.navbar-toggler-filter,
.navbar-expand-filters{
    padding:0px;
}

.btn-search-submit{
    padding-left:30px;
    padding-right:30px;
}

.type-count, .type-count-all{
    color: #6c757d;
    display:block;
    font-weight:normal;
    margin-left:17px;
}

.search-nav-tabs .nav-link{
    padding-bottom:0px;
}

#search-keywords {
    border:1px solid #007bff
}

.search-count{
    font-size:1.5rem;
    color:#343a40;
}

 /* Chrome/Opera/Safari */
#search-keywords::-webkit-input-placeholder {
  color: #dee2e6;
}

/* Firefox 19+ */
#search-keywords::-moz-placeholder { 
    color: #dee2e6;
}

/* IE 10+ */
#search-keywords:-ms-input-placeholder { 
    color: #dee2e6;
}

/* Firefox 18- */
#search-keywords:-moz-placeholder { 
    color: #dee2e6;
}


.round-bg{
  display: inline-block;
  border-radius: 60px!important;
  box-shadow: 0px 0px 2px #888;
  padding: 0.5em 0.6em;
  font-size:25px;
}

.count{
    color:gray;
    font-size:smaller;
}

.sidebar-filter .form-check-input{
    margin-top:.25rem;
}

.lnk-filter-all,
.lnk-filter-reset{
    color:#007bff;
    font-size:small;
    cursor:pointer;
}

.lnk-filter-all:hover,
.lnk-filter-reset:hover{
    color:black;
}

.fa-stack { 
    font-size: 2em;
    color:#bfbfbf;
}

.collapsed .icon-expanded{
    display:none;
}
.collapsed .icon-collapsed{
    display:inherit;
}

.icon-collapsed{
    display:none;
}

.study-idno{
    color:gray;
    font-size:12px;
}

h5{margin:0px;}


.variable-search .vrow{
    font-size:medium;
}

.variable-search .vrow .var-subtitle{
    color:gray;
    font-size:small
}

.study-view-toggle a:hover,
.study-view-toggle .toggle_view{
    color:white;
}

.variable-comparison-popup{
    display:none;
}

.study-thumbnail{
    object-fit: cover;
    max-height: 180px;
    width: 100%;
}

.clear-disabled{display:none}


.survey-row .icon-da-sm{
    width:14px;
    height:14px;
    background-size:12px;
}

.btn-data-access{
    font-size:small;
    padding:0.12rem .5rem !important
}

.entry-flags .badge{
    font-weight:normal;
}

</style>

<div class="container">
<form method="get" id="catalog-search-form">    
    <input type="hidden" name="tab_type" id="tab_type" value="<?php echo form_prep($search_options->tab_type);?>"/>
    <input type="hidden" name="page" id="page" value="<?php echo $search_options->page;?>"/>
    <input type="hidden" name="view" id="view" value="<?php echo $search_options->view;?>"/>
    <input type="hidden" name="image_view" id="image_view" value="<?php echo form_prep($search_options->image_view);?>"/>

    <?php if($search_box_orientation!=='inline'):?>
        <!--search bar-->
        <?php echo $this->load->view('search/keyword_search_box',null, true);?>

        <?php if($data_types_nav_bar==true && $this->input->get("view")!=='v' ):?>
            <!-- data types nav tabs -->
            <?php echo $this->load->view('search/search_data_tabs',array('tabs'=>$tabs,'type_icons'=>@$type_icons), true);?>
        <?php endif;?>
    <?php endif;?>


<div class="row">
    <!--left side bar -->
    <div class="col-12 col-lg-3 col-md-4">

        <nav class="navbar navbar-expand-sm navbar-expand-filters">
            
            <button class="navbar-toggler btn-block navbar-toggler-filter" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="btn btn-outline-secondary btn-block" xstyle="font-size:12px"><i class="fa fa-sliders" aria-hidden="true"></i> Filters</span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <div class="filters-container">
                    <?php foreach($filters as $filter):?>
                        <?php echo $filter;?>
                    <?php endforeach;?>
                </div>
            </div>                        
        </nav>

    </div>
    <!-- end left side bar -->



    <!-- listing page -->
    <div class="col-lg-9 col-md-8">

    <?php if($search_box_orientation=='inline'):?>
        <!--search bar-->
        <?php echo $this->load->view('search/keyword_search_box',null, true);?>

        <?php if($data_types_nav_bar==true || $this->input->get("view")!=='v'):?>
            <!-- data types nav tabs -->
            <?php echo $this->load->view('search/search_data_tabs',array('tabs'=>$tabs,'type_icons'=>@$type_icons), true);?>
        <?php endif;?>
    <?php endif;?>

        <div id="search-result-container">
            <?php echo $search_output;?>
        </div>
        
    </div>
    <!-- end listing-->




</div>
</form>

</div>


<!-- variable comparison -->
<div class="variable-comparison-popup-backdrop wb-collapsed"></div>
<div class="fixed-bottom variable-comparison-popup wb-collapsed">

    <div class="container pt-2 mt-4 mb-3 d-none d-md-block" >
       <div class="row wb-var-panel">
           <div class="col-12 wb-var-title">
               <div class="align-middle mt-2">
                   <?php echo t("Compare variables");?>
                   <span class="var-in-cart badge badge-light"></span>
                </div>
               <div>
                    <i class="fa fa-angle-up wb-var-handler-up"></i>
                    <i class="fa fa-angle-down wb-var-handler-down"></i>
               </div>
            </div>
            <div class="col-12 var-list wb-var-body">

                <!--<span class="var-box">
                <i class="fa fa-address-card" aria-hidden="true"></i>
                <i class="fa fa-window-close faclose var-remove" aria-hidden="true"></i>
                </span>-->
    
            </div>
           <div class="col-12 wb-var-actions">
               <div class="align-middle mt-2"> 
                    <button class="btn btn-link btn-sm rounded clear-variables"><?php echo t("Clear");?></button>
                    <a target="_blank" class="btn btn-primary btn-sm rounded" href="<?php echo site_url('catalog/compare');?>"><?php echo t("Compare");?></a>                   
                </div>
           </div>
       </div>
    </div>
</div>
<!-- end variable comparison -->



<?php 
//search plugins
if (file_exists('application/views/search/search_plugin.php')){
    $this->load->view('search/search_plugin.php');
}
?>


<script>
//translations	
var i18n=
{
    'searching':"<?php echo t('js_searching');?>",
    'loading':"<?php echo t('js_loading');?>",
    'invalid_year_range_selected':"<?php echo t('js_invalid_year_range_selected');?>",
    'topic_selected':"<?php echo t('js_topic_selected');?>",
    'topics_selected':"<?php echo t('js_topics_selected');?>",
    'collection_selected':"<?php echo t('js_collection_selected');?>",
    'collections_selected':"<?php echo t('js_collections_selected');?>",
    'country_selected':"<?php echo t('js_country_selected');?>",
    'countries_selected':"<?php echo t('js_countries_selected');?>",
    'cancel':"<?php echo t('cancel');?>",
    'apply_filter':"<?php echo t('apply_filter');?>",
    'clear':"<?php echo t('clear');?>",
    'js_compare_variables_selected':"<?php echo t('variables selected from');?>",
    'js_compare_studies_selected':"<?php echo t('studies');?>",
    'js_compare_variable_select_atleast_2':"<?php echo t('Select two or more variables to compare');?>",
    'js_compare_variable_max_limit':"<?php echo t('You have selected the maximum variables to compare');?>",
    'selected':"<?php echo t('selected');?>"
};

    $(".variable-comparison-popup .wb-var-title" ).click(function() {
        $(".variable-comparison-popup" ).toggleClass("wb-collapsed");
    });

    //cookie helper functions
    //source: http://www.quirksmode.org/js/cookies.html
    function createCookie(name,value,days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires="+date.toGMTString();
        }
        else var expires = "";
        document.cookie = name+"="+value+expires+"; path=/";
    }

    function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }

    function eraseCookie(name) {
        createCookie(name,"",-1);
    }

    function compare_var_summary(){
		var sel_items=readCookie("variable-compare");
		
		if(sel_items==null || sel_items==''){
			sel_items=Array();
		}
		else{
			sel_items=sel_items.split(",");
		}
								
		//get unique study count
		var studies=[];
		for (var i = 0; i < sel_items.length; i++) {
			if(sel_items[i].indexOf("/") !== -1){
				var item=sel_items[i].split("/");
				if($.inArray(item[0], studies)==-1){
					studies.push(item[0]);
				}
			}
		}//end-for
		
		if(sel_items.length==0){
			$(".variables-found .var-compare-summary").html( i18n.js_compare_variable_select_atleast_2);
		}
		else{				
			$(".variables-found .var-compare-summary").html( sel_items.length + " " + i18n.js_compare_variables_selected + " " + studies.length + " " + i18n.js_compare_studies_selected);
		}
    }


    function get_selected_variables(){
        var sel_items=readCookie("variable-compare");
        
        if(sel_items==null || sel_items==''){
            sel_items=Array();
        }
        else{
            sel_items=sel_items.split(",");
        }

        return sel_items;
    }

    function update_compare_variable_list(action,value){
        var sel_items=readCookie("variable-compare");
        
        if(sel_items==null || sel_items==''){
            sel_items=Array();
        }
        else{
            sel_items=sel_items.split(",");
        }

        switch(action)
        {
            case 'add':
                if(sel_items.length>9){
                    alert(i18n.js_compare_variable_max_limit);
                    return false;
                }
                if($.inArray(value, sel_items)==-1){
                    sel_items.push(value);
                }
                break;
            
            case 'remove':
                var index_matched=$.inArray(value, sel_items);
                if(index_matched>-1){
                    sel_items.splice(index_matched,1);
                }			
                break;
            
            case 'remove-all':
                eraseCookie("variable-compare");return;
            break;
        }
        
        //update cookie
        createCookie("variable-compare",sel_items,1);
    }

    //toggle compare variable popup
    function compare_variable_popup_toggle(){
        vars=get_selected_variables();
        if(vars.length>0){
            $(".variable-comparison-popup").show();
        }else{
            $(".variable-comparison-popup").hide();
        }
    }

    //create variable item
    function compare_variable_popup_update(){

        vars=get_selected_variables();
        /*$(".variable-comparison-popup").addClass("wb-updating");
        setTimeout(function() { 
            $(".variable-comparison-popup").removeClass("wb-updating");
        }, 1000);*/
        
        $(".variable-comparison-popup .var-list").html("Loading...");
        
        $.get(CI.base_url + '/catalog/variable_cart', function(data) {
            console.log(data);
            $(".variable-comparison-popup .var-list").html("");
        
            /*for(var i=0;i < vars.length;i++) {
                var var_info=vars[i].split("/");
                var url=encodeURI(CI.base_url + '/catalog/' + var_info[0] + '/variable/' + var_info[1]);
                var var_id=vars[i].replace("/","__");

                var html =`<span class="var-box" id="var-${var_id}" data-value="${vars[i]}">
                    <!--<a target="_blank" href="${url}"><i class="fa fa-address-card" aria-hidden="true"></i></a>-->
                    <a class="text-white" target="_blank" href="${url}">EVENT_ID_CNTRY</a>
                    <i class="fa fa-window-close faclose var-remove" aria-hidden="true" data-value="${vars[i]}"></i>
                </span>`;

                $(".variable-comparison-popup .var-list").append(html);
            }*/

            if(data.length<1){
                update_compare_variable_list("remove-all");
                compare_variable_popup_toggle();
            }
            
            for(var i=0;i < data.length;i++) {    
                var row=data[i];
                var url=encodeURI(CI.base_url + '/catalog/' + row['sid'] + '/variable/' + row['vid']);

                var html =`<div class="var-box" id="var-${row['sid']}__data['vid']" data-value="${row['sid']}/${row['vid']}">
                    <!--<a target="_blank" href="${url}"><i class="fa fa-address-card" aria-hidden="true"></i></a>-->
                    <a class="text-white font-weight-bold var-name dlg link" target="_blank" data-url="${url}" href="${url}">${row['name']}</a>
                    <div class="text-light var-description">${row['idno']}</div>
                    <i class="fa fa-times faclose var-remove" aria-hidden="true" data-value="${row['sid']}/${row['vid']}"></i>
                </div>`;

                $(".variable-comparison-popup .var-list").append(html);
            }

            $(".variable-comparison-popup .var-in-cart").html(data.length);
        })
        .fail(function() {
            alert("error");
        })

        
    }

    //remove all variables
    function compare_variable_clear(){
        eraseCookie("variable-compare");
        compare_variable_popup_update();
        compare_variable_popup_toggle();
        return;
    }

    function compare_variable_refresh_selection(){
        vars=get_selected_variables();
        //uncheck all 
        $(".compare").prop( "checked", false );

        //check selected only
        for(var i=0;i < vars.length;i++) {
            $(".compare[value='"+vars[i]+"']").prop("checked",true);
        }

        compare_variable_popup_update();
    }


    //update selected variables on page/tab changes
    $(document).on('visibilitychange', function() {

        if(document.visibilityState == 'visible') {
            compare_variable_refresh_selection();
        }
    });

    $(document).ready(function(){
        compare_variable_popup_update();
        compare_variable_popup_toggle();
    });

    function toggle_clear(el){

        console.log($(el).closest(".items-container").find(".chk:checked").length);

        if ($(el).closest(".items-container").find(".chk:checked").length >0){
            $(el).closest(".sidebar-filter").find(".clear-button-container").removeClass("clear-disabled").addClass("clear-enabled");
            $(el).closest(".sidebar-filter").find(".clear-button-container").find(".selected-items").text($(el).closest(".items-container").find(".chk:checked").length);
        }else{
            $(el).closest(".sidebar-filter").find(".clear-button-container").removeClass("clear-enabled").addClass("clear-disabled");
        }
    }
    
    function init_facet_stats(el){    
        let selected_items=$(el).find(".items-container").find(".chk:checked").length;
        let total_items=$(el).find(".items-container").find(".chk").length;

        if (selected_items >0){
            $(el).closest(".sidebar-filter").find(".clear-button-container").removeClass("clear-disabled").addClass("clear-enabled");
            $(el).closest(".sidebar-filter").find(".clear-button-container").find(".selected-items").text(selected_items);
            $(el).closest(".sidebar-filter").find(".clear-button-container").find(".total-items").text(total_items);
        }else{
            $(el).closest(".sidebar-filter").find(".clear-button-container").removeClass("clear-enabled").addClass("clear-disabled");
        }
    }

    //toggle checkboxes on page load
    function refresh_facet_options(){
        jQuery.each($(".sidebar-filter"), function( i, el ) {
            init_facet_stats($(el));
        });     
    }

    $(function() {
        refresh_facet_options();
    });

    
    //clear variable selection
    $(document.body).on("click",".clear-variables", function(){                    
        compare_variable_clear();
        compare_variable_refresh_selection();
        return false;
    });

    $(document.body).on("click",".var-remove", function(e){
        let id=$(this).attr('data-value');
        $(".compare[value='"+id+"']").trigger("click");
        update_compare_variable_list("remove",id);
        compare_variable_refresh_selection();
        return false;
    });
    

$(document).ready(function() {
    var page_first_load=true;
    toggle_reset_search_button();
    var State=History.getState();

    if(!State.data.page_state_data){        
        console.log("setting first loaded page state");
        page_first_load=false;
        let search_state=$("#catalog-search-form :input[value!='']").serialize();
        let page_state_data={
                'search_options': $("#catalog-search-form").serializeArray(),
                'search_results': null
            };
        History.replaceState({state:search_state,page_state_data}, document.title, "?"+search_state);
    }else{
        load_current_state();
        toggle_reset_search_button();
    }
    

    function reset_page(){
        $("#page").val(1);
    }    

    //submit search form
    $(document.body).on("click","#submit_search", function(){                    
        reset_page();
        change_state();
        return false;
    });


    //change page size
    $(document.body).on("click",".change-page-size", function(){                    
        ps=$(this).attr("data-value");
        console.log(ps);
        $("#page").val(1);
        $("#ps").val(ps);
        change_state();
    });

    

    $(document.body).on("click",".remove-filter", function(){

        name=$(this).attr("data-type");
        value=$(this).attr("data-value");

        el_name="[name='" + name + "']," + "[name='" + name + "[]']";
        elements=$(el_name);

        if (name=='years'){
            $("#from").val("");
            $("#to").val("");
        }

        if (elements.length>0){
            if (elements.prop("type")=='checkbox'){
                named_el=$("[name='" + name + "'][value='"+value+"']");
                console.log(named_el);
                named_el.trigger('click');
                console.log(named_el);
            }
            else if(elements.prop("type")=='text' || elements.prop("tagName").toLowerCase()=='select'){
                elements.prop("value",'');
            }
        }
        

        $(this).hide();
        change_state();
        
    });


    function search(){
        search_state=$("#catalog-search-form").serialize();
        $( "#search-result-container" ).html('Loading, please wait ...');

        $.get('<?php echo site_url('catalog/search');?>?'+search_state, function( data ) {
            $( "#search-result-container" ).html( data );
            let page_state_data={
                'search_options': $("#catalog-search-form").serializeArray(),
                'search_results': null
            };

            //reset nav-tabs
            $(".dataset-type-tab").find(".type-count").html("0");

            //update nav-tabs
            let types_summary=$(".type-summary").attr("data-types");

            if(types_summary){
                types_summary=JSON.parse(types_summary);
                jQuery.each(types_summary, function(data_type, counts ) {
                    $(".dataset-type-tab-"+data_type).find(".type-count").html(parseInt(counts).toLocaleString());
                });
            }

        });        
    }


    //call this for search
    function change_state(){
        console.log("change_state called");
        let search_state=serialize_form(); //don't include empty elements
        let page_state_data={
                'search_options': $("#catalog-search-form").serializeArray(),
                'search_results': null
            };
            
        History.pushState({state:search_state,page_state_data}, document.title + '/search - ' + search_state, "?"+search_state);
    }

    function serialize_form(){
        return  $("#catalog-search-form :input").filter(function(index, element) {
            return $(element).val() != '';
        }).serialize();
    }


    //sort dropdown
    $(document.body).on("change","#sort-by-select", function(){
        let sort_order=$(this).find(':selected').data('sort');
        let sort_by=$(this).val();
        window.x=$(this);
        $("#sort_by").val(sort_by);
        $("#sort_order").val(sort_order);
        change_state();
    });

    $(document.body).on("click",".dataset-type-tab", function(){
        $( ".chk-type").prop("checked",false);
        el=$("[name='type[]'][value='"+ $(this).attr("data-value") +"']");
        el.prop("checked",true);
        reset_page();
        $( "#tab_type" ).val($(this).attr("data-value"));
        
        window.location.href='<?php echo site_url('catalog');?>?'+$("#catalog-search-form").serialize();
        return false;
    });

    //pagination link
    $(document.body).on("click",".pagination .page-link", function(){        
        $( "#page" ).val($(this).attr("data-page"));
        change_state();
        return false;
    });

    //check/select filter
    $(document.body).on("change",".filters-container .chk, .filters-container select", function(){        
        change_state();
        toggle_clear(this);
    });

    //clear filter
    $(document.body).on("click",".filters-container .lnk-filter-reset", function(){        
        $(this).closest(".filter-box").find(".chk").prop("checked",false);
        change_state();
        toggle_clear(this);
    });
    
    $(document.body).on("keypress",".search-keywords", function(e){    
        var code = e.keyCode;
        toggle_reset_search_button();
        if(code==13){
            $('#submit_search').trigger("click");
            return false;
        }
    });
    


    History.Adapter.bind(window,'statechange',function(){ // Note: We are using statechange instead of popstate        
        var State = History.getState(); // Note: We are using History.getState() instead of event.state        
        //$( "#catalog-search-form" ).html( State.data.data );
        window.data=State.data;
        console.log("loading state");
        console.log(State);
        //$( "#search-result-container" ).html(State.data.search_results);        

        load_current_state();
    });



    function load_current_state(){
        if(page_first_load==true){
            page_first_load=false;
            return;
        }

        var State=History.getState();

        if(!State.data.page_state_data){
            return false;
        }

        reset_all_filters();
        jQuery.each(State.data.page_state_data.search_options, function( i, field ) {
            elements=$("[name='" + field.name + "']");
            
            if (elements.prop("type")=='checkbox'){
                named_el=$("[name='" + field.name + "'][value='"+field.value+"']");
                named_el.prop("checked",true);
            }
            else if(elements.prop("type")=='text' || elements.prop("tagName").toLowerCase()=='select'){
                elements.prop("value",field.value);
            }
        });

        //only time search function should be called
        search();
    }


    function reset_all_filters(){
        //uncheck all checkboxes
        $(".filters-container .chk").prop("checked",false);

        //reset  select
        $(".filter-container .form-control").prop("value",'');
    }

    function toggle_reset_search_button(){
        if (!$("#search-keywords").val()){
            $(".clear-search-button").hide();
        }
        else{
            $(".clear-search-button").show();
        }
    }


    //show/hide study sub-variable search
    $(document.body).on("click",".vsearch", function(event){
        event.stopPropagation();
		$(this).parent().toggleClass("expand");
		var result=$(this).parent().find(".vsearch-result");
		if (result.html()!='' ){
			result.empty().hide();
		}
		else{
			result.show().html('<span class="fa fa-circle-o-notch fa-spin fa-2x text-primary"></span><span>Loading</span>').load($(this).prop("href"), function(data){
				
				//attach compare handlers
				//variable_compare_handlers();
			});
			//result.parent().find(".open-close").prop("src",'images/arrow_down.gif');
		}
		//compare_var_summary();
		return false;
    })


    //show variable details in a modal dialog
    $(document.body).on("click",".variable-list .vrow .link", function(event){
        event.stopPropagation();

        var vrow=$(this).closest(".vrow");
        var target='';
        if(typeof $(vrow).attr("data-url-target") != 'undefined'){
            target=$(vrow).attr("data-url-target");
        }
        if(target==''){
            window.location=$(vrow).attr("data-url");
        }
        else{
            window.simple_dialog("dialog_id",$(vrow).attr("data-title"),$(vrow).attr("data-url"));return false;
        }
    });

    //variable info for compare variable
    $(document.body).on("click",".var-box .var-name", function(event){        
        event.stopPropagation();
        window.simple_dialog("dialog_id",$(this).text(),$(this).attr("href"));return false;
    });



    /////////////////////////////////////////////////////////////////////////////////////////////
	// simple dialog
	/////////////////////////////////////////////////////////////////////////////////////////////
	window.simple_dialog=function simple_dialog(dialog_id,title,data_url){
		if($("#"+dialog_id).length ==0) {
            $("body").append('<div class="modal fade" id="'+dialog_id+'" tabindex="-1" role="dialog"  aria-hidden="true">\
                <div class="modal-dialog  modal-lg catalog-modal-dialog" role="document">\
                <div class="modal-content">\
                <div class="modal-header">\
                <h5 class="modal-title" id="'+dialog_id+'Label">'+title+'</h5>\
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">\
						<span aria-hidden="true">&times;</span>\
					</button>\
					</div>\
					<div class="modal-body">\
				</div>\
					<div class="modal-footer">\
						<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>\
					</div>\
					</div>\
					</div>\
					</div>');
		}
		else
		{
            /********* for jQuery ui modal *******/
			/*dialog=	$("#"+dialog_id);
			dialog.html("loading...");
			dialog.dialog({ title: title});*/

            /********* for Bootstrap modal *********/
            $('#'+dialog_id+' h5.modal-title').html(title);
            $('#'+dialog_id+' div.modal-body').html("loading...");
        }
        
        // for jQuery ui modal
		/*
		dialog.dialog( "open" ); // for jQuery ui modal
        //$('#'+dialog_id).load(data_url+'?ajax=1');//load content
        */

        // for Bootstrap modal
		$('#'+dialog_id).modal('show');// for Bootstrap modal
        $('#'+dialog_id+' div.modal-body').load(data_url+'?ajax=1');//load content
	}//end function


    

    //compare checkbox click

    //compare button
    $(document.body).on("click",".btn-compare-var", function(event){
        event.stopPropagation();
        var sel_items=readCookie("variable-compare");
        
        if(sel_items==null){
            sel_items=Array();
        }
        else{
            sel_items=sel_items.split(",");
        }
        if(sel_items.length>1){
            window.open(CI.base_url+'/catalog/compare','compare');
        }
        else{
            alert(i18n.js_compare_variable_select_atleast_2);return false;
        }
        return false;
    });
        
                
    $(document.body).on("click",".compare", function(event){
        var sel_items=readCookie("variable-compare");
        
        if(sel_items==null){
            sel_items=Array();
        }
        else{
            sel_items=sel_items.split(",");
        }

        if ($(this).prop("checked")){
            update_compare_variable_list('add',$(this).val());
        }
        else{
            update_compare_variable_list('remove',$(this).val());
        }
        
        compare_var_summary();
        compare_variable_popup_update();
        compare_variable_popup_toggle();
    });

    
        
    //disable even propogations for compare link
    $(document.body).on("click",".var-quick-list .compare-variable", function(event){
            event.stopPropagation();
    });

});
    
</script>
