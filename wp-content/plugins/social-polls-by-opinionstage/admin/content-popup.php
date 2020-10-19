<?php
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

add_action( 'media_buttons', 'opinionstage_content_popup_add_editor_button');
add_action( 'admin_enqueue_scripts', 'opinionstage_content_popup_js');
add_action( 'admin_footer', 'opinionstage_content_popup_html' );
add_action( 'admin_footer', 'opinionstage_content_popup_css_dropdown' );

$opinionstage_user_logged_in = opinionstage_user_logged_in();

if ( !$opinionstage_user_logged_in ) {
	add_action( 'admin_footer', 'opinionstage_content_popup_css_without_login' );
}

function opinionstage_content_popup_add_editor_button() {
	require( plugin_dir_path( __FILE__ ).'content-popup-button.php' );
}

function opinionstage_content_popup_js() {

	// asset loader hotfix TODO: improve this loader machanism 
		opinionstage_register_javascript_asset(
			'content-popup',
			'content-popup.js',
			array('jquery')
		);

		opinionstage_enqueue_js_asset('content-popup');	
}

function opinionstage_content_popup_html() {
	require_once OPINIONSTAGE_PLUGIN_DIR . 'admin/content-popup-template.html.php';
}

function opinionstage_content_popup_css_without_login(){ ?>
<style type="text/css">
	.opinionstage-content-popup-contents .page-content {
		padding-left: 20px;
    	padding-right: 20px;
    	padding-bottom: 25px;
	}
	.opinionstage-content-popup .tingle-modal-box {
		height: auto;
		max-width: 600px;
		min-width: 400px;
	}
	.opinionstage-content-popup-contents .main-title {
		font-size: 20px;
		margin-bottom: 15px;
		margin-left: 15px;
		text-align: left;
	}
	.opinionstage-content-popup-contents .opinionstage-blue-btn {
	    margin: 15px;
	}
	.components-button.is-default.is-block.is-primary {
		max-width: 130px;
	}
</style>
<?php }

function opinionstage_content_popup_css_dropdown(){ ?>
	<style type="text/css">
.dropbtn {
    background-color: #ffffff;
    border: 1px solid #e4e4e4;
    border-right: 0 !important;
    box-shadow: 0 0 0 !important;
    cursor: pointer;
    display: inline-block;
    font: 16px/42px Open Sans,Helvetica,sans-serif;
    outline: none!important;
    padding-left: 10px;
    position: relative;
    text-align: left;
    text-decoration: none;
    width: 140px;
}

.dropdown {
  display: inline-block;
  position: relative;
}

.dropdown-popup-action {
  height: 45px;
}

.dropdown-content {
  background-color: #f9f9f9;
  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
  display: none;
  left: 1px;
  position: absolute;
  top: 40px;
  z-index: 9;
}

.dropdown-content div {
    background-color: #fff;
    border: 1px solid #3487fa;
    border-bottom: 0 !important;
    border-top: 0 !important;
    color: #555454;
    display: block;
    padding: 5px 10px 5px 20px;
    text-decoration: none;
}

.dropdown-content div:hover {background-color: #3487fa; color: #fff !important;}

.dropdown_items .dropdown-content {
  width: 180px;
}
.dropdown:hover .dropdown-content {
  display: block !important;
  width: 180px;
}
.dropdown:hover .content-item-menu {
  display: block;
}
.dropdown:hover .dropbtn {
  background-color: #ffffff;
}
.opinionstage-content-popup-contents .filter__itm{
  font-size: 12px !important;
  line-height : 25px !important;
  margin-right: 0 !important;
  width: 100%;
}
button#dropbtn span {
    color: #555454;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
}
.filter {
     margin: 0 !important; 
}
button#dropbtn:before {
    color: #000;
    content: "";
    display: block;
    font-family: os-icon-font !important;
    font-size: 9px;
    position: absolute;
    right: -25px;
    top: 0;
    z-index: 3;
    font-weight: bold;
}
button#dropbtn:after {
    border: 1px solid #e4e4e4;
    border-left: 0 !important;
    content: "";
    height: 45px;
    position: absolute;
    right: -40px;
    top: -1px;
    width: 40px;
}
.opinionstage-content-popup-contents .filter__itm.active{
    background: #5299fb;
    color: #fff;
    font-weight: normal !important;
}
.filter__itm:last-child {
    border-bottom: 1px solid #3487fa !important;
}
.filter__itm:first-child {
    border-top: 1px solid #3487fa !important;
}

.opinionstage-content-popup-contents .std-search {
    background: #ffffff;
    border: 1px solid #cccccc;
    border-radius: 2px;
    box-shadow: 0 0;
    box-sizing: border-box;
    color: #797979;
    font-size: 14px;
    height: 40px;
    padding: 10px;
    width: 100%;
}
.opinionstage-content-popup-contents .search{
  float: right;
}
.search:before {
    bottom: 0;
    color: #3aaebd;
    content: "";
    font-family: os-icon-font !important;
    font-size: 18px;
    height: 18px;
    line-height: normal;
    margin: auto;
    pointer-events: none;
    position: absolute;
    right: 10px;
    top: 0;
}

.header-right-container {
    float: right;
}
.header-right-inner-container {
    border-right: 1px solid #e5e5e5;
    float: left;
    padding: 0 20px;
}
.opinionstage-content-popup-contents .page-content {
  padding: 30px;
}
.opinionstage-content-popup-contents .filter {
    width: auto;
}
.opinionstage-content-popup-contents .filter .dropdown .dropbtn {
    margin-right: 50px !important;
}
.opinionstage-content-popup-contents .filter #dropbtn {
    font: 12px/38px Open Sans,Helvetica,sans-serif;
}
.opinionstage-content-popup-contents .filter button#dropbtn:after {
    height: 38px;
}
.opinionstage-content-popup-contents .btn-close {
    border: none;
    font: 24px/38px Open Sans,Helvetica,sans-serif;
    opacity: 1;
}
.popup-header-item-create-btn {
    background-color: #32adbc;
    border-radius: 3px;
    border-style: hidden;
    color: #fff;
    cursor: pointer;
    display: inline-block;
    font-size: 15px;
    font-weight: 600;
    line-height: normal;
    margin-left: 20px;
    padding: 10px 11px;
    text-decoration: none;
}
.popup-header-item-create-btn:hover {
    color: #fff;
    opacity: 0.9;
    text-decoration: none;
}
.popup-header-item-create-btn:before {
    bottom: 0;
    content: "+";
    font-family: os-icon-font;
    font-size: 17px;
    font-weight: bold;
    height: 18px;
    margin-right: 8px;
    pointer-events: none;
    top: 0;
}
.opinionstage-content-popup-contents .content__label {
	bottom: auto;
	font-size: 12px;
	padding: 1px 5px;
	top: 0;
}
.popup-header-title {
    float: left;
    font-size: 30px;
    font-weight: normal;
    line-height: normal;
}
.opinionstage-content-popup-contents .content__itm {
  border-bottom: 1px solid #e5e5e5;
  display: block;
  padding-bottom: 6px;
  padding-top: 10px;
  margin: 0;
  width: 100%;
}
.opinionstage-content-popup-contents .content__itm:first-child {
  border-top: 1px solid #e5e5e5;
}
.opinionstage-content-popup-contents .content__image {
  display: inline-block;
  height: 90px;
  width: 15%;
}
.opinionstage-content-popup-contents .content__info {
    background: transparent;
    display: inline-block;
    font-size: 16px;
    line-height: normal;
    padding: 0 10px;
    width: 50%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.opinionstage-content-popup-contents .content__info a, .opinionstage-content-popup-contents .content__info a:hover {
  color: inherit;
  text-decoration: none;
}
.opinionstage-content-popup-contents .content__itm:hover .content__links {
    display: inline-block;
}
.opinionstage-content-popup-contents .content__links {
  background: none;
  display: inline-block;
  float: right;
  opacity: 1;
  padding: 20px 10px;
  position: relative;
  text-align: right;
  width: 30%;
  z-index: unset;
}
.opinionstage-content-popup-contents .popup-content-btn {
    border: 1px solid #32adbc;
    border-radius: 2px;
    color: #32adbc;
    display: inline;
    font-size: 15px;
    font-weight: 600;
    padding: 13px 14px;
    text-align: center;
    text-decoration: none;
    width: auto;
}
.popup-action.popup-content-btn {
    margin-left: 10px;
    padding: 12px 25px;
    position: relative;
}
.popup-action:before {
    color: #32adbc;
    padding: 0;
    position: absolute;
    right: 18px;
    content: "";
    display: block;
    font-family: os-icon-font !important;
    font-size: 9px;
    top: 15px;
    z-index: 3;
    font-weight: bold;
}

.popup-action-dropdown {
    background: #fff;
    border: 1px solid #32adbc;
    border-radius: 3px;
}
.popup-action-dropdown .content__links-itm {
    border: none;
    border-bottom: 1px solid #32adbc;
    border-radius: 0;
    color: #32adbc;
    font-size: 14px;
    font-weight: normal;
    margin: 0;
    padding: 10px 10px;
    width: auto;
    text-align: left !important;
    letter-spacing: 1px;
}
.popup-action-dropdown .content__links-itm:last-child {
    border: none;
}
.popup-action-dropdown.dropdown-content {
	left: auto;
	right: 0;
	top: 20px;
  	z-index: 10;
}
.dropdown:hover .popup-action-dropdown.dropdown-content {
    width: 130px;
}

.top-arrow-box {
    position: relative;
}
.top-arrow-box .top-arrow {
    background: #ffffff;
    position: absolute;
    right: 16px;
    width: 10px;
    top: 1px;
}

.content-item-menu {
    display: none;
    position: absolute;
    right: 0;
    z-index: 100;
}

a.content__links-itm:hover {
    background-color: #32adbc !important;
    color: #fff;
}
.dropdown:hover .popup-action.popup-content-btn {
    background-color: #32adbc;
}
.dropdown:hover .popup-action.popup-content-btn:before {
    color: #fff;
}

@media only screen and (max-width: 875px) {
.opinionstage-content-popup .tingle-modal-box {
  	min-width: 100%;
  	overflow: hidden;
    width: 100%;
}
}

@media only screen and (max-width: 767px) {
.opinionstage-content-popup-contents .content__links {
    padding-left: 0;
    padding-right: 0;
}
}

	</style>
<?php }
