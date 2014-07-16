<?php
 /*
 Plugin Name: kingkongcart
 Plugin URI: http://www.ithemeso.com
 Description: 대한민국 대표 워드프레스 쇼핑몰 플러그인 킹콩카트 입니다.
 Version: 0.2.2
 Author: ithemeso
 Author URI: http://www.ithemeso.com
 License: GPL2

 /* Copyright 2014 ithemeso (email : ithemeso@naver.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/
/**
 *
 * @package kingkongcart
 * @category Core
 * @author ithemeso 
 */

define(KINGKONG_CART_ID, get_option("kingkongcart_mypage_cart"));     // 장바구니 아이디
define(KINGKONGCART_ORDER, get_option("kingkongcart_order_page"));    // 주문하기 페이지 아이디
define(KINGKONG_MYPAGE, get_option("kingkongcart_mypage_mypage"));    // 마이페이지 아이디
define(KINGKONG_JOIN, get_option("kingkongcart_join_member"));        // 회원가입 아이디
define(KINGKONG_WISH, get_option("kingkongcart_mypage_wish"));        // 위시리스트 아이디
define(KINGKONG_BROKE, get_option("kingkongcart_mypage_broke"));      // 회원탈퇴 페이지 ID
define(KINGKONG_PROFILE, get_option("kingkongcart_mypage_profile"));  // 내정보수정 아이디
define(KINGKONG_ORDER, get_option("kingkongcart_mypage_order"));      // 주문내역 아이디
define(KINGKONG_POLICY, get_option("kingkongcart_policy"));           // 이용약관 아이디
define(KINGKONG_PRIVACY, get_option("kingkongcart_privacy"));         // 개인정보취급방침 아이디
define(KINGKONG_MAIN, get_option("kingkongcart_main"));               // 메인페이지 아이디
define(KINGKONG_LOGIN, get_option("kingkongcart_login"));             // 킹콩카트 로그인 아이디 
define(KINGKONG_KEY, get_option("kingkongcart_update_key"));          // 킹콩카트 업데이트 키
define(MILEAGE_TEXT, "적립금");
define(CURRENCY_TEXT, "원");
define(KINGKONGCART_ABSPATH, plugin_dir_path(__FILE__));            // 플러그인 절대경로
define(KINGKONGCART_PLUGINS_URL, plugins_url('', __FILE__));            // 플러그인 상대경로
define(KINGKONGCART_PRODUCT_PREFIX, get_option("kingkongcart_product_prefix")); //제품번호 프리픽스
define(KINGKONGCART_ORDER_PREFIX, get_option("kingkongcart_order_prefix"));     //주문번호 프리픽스
define(KINGKONGCART_BOARD_PATH, plugins_url('', __FILE__)."/includes/board/skin/"); // board 경로

require_once 'ajax/kingkongcart-ajax.php';									// AJAX
require_once 'core/admin/kingkong-core.php';       							// Core Setting Functions
require_once 	'core/admin/meta-functions/kkcart_product-thumbnail.php';	// 썸네일 이미지 등록 메타필드
require_once	'core/admin/meta-functions/kkcart_product_meta_price.php';	// 판매가격 설정 메타필드
require_once	'core/admin/meta-functions/kkcart_post-save-action.php';	// 포스트 세이브 할때 메타 정보 저장
require_once 'includes/admin/dashboard.php';								// Admin Dashboard 
require_once 'includes/admin/balance.php';                  // Admin 정산관리
require_once 'includes/admin/quantity.php';                 // Admin 재고관리
require_once 'includes/admin/board-manage.php';             // Admin 문의관리
require_once 'includes/admin/admin-order.php';              // Admin Order List
require_once 'functions/functions.php';										// kingkongcart functions
require_once 'functions/ajax-functions.php';              // AJAX
require_once 'includes/product-loop.php';									// Product Loop
require_once 'includes/mypage.php';											// 마이페이지(장바구니,프로필,위시리스트 등등)
require_once 'includes/join-member.php';                // 회원가입
require_once 'includes/order.php';                        // 주문하기 페이지
require_once 'includes/board/qna.php';                     // 게시판:Q&A 숏코드
require_once 'includes/board/afternote.php';              // 게시판:이용후기 숏코드


/* 킹콩카트 초기 설정정보 init ***************************************************************************/

register_activation_hook(__FILE__,'kingkongcart_activate'); 

function kingkongcart_activate(){

	$general_option = array(
		'fonts'				=> "malgun",
		'display' 			=> "thumbnail-title-price",
		'grid-width' 		=> "100%",
		'row-num'			=> "6",
		'title-color'		=> "#000000",
		'shortdesc-color' 	=> "#000000",
		'price-color'		=> "#000000",
		'mileage-color'		=> "#000000",
		'discount-color'	=> "#000000",
		'title-size'		=> "14px",
		'shortdesc-size'	=> "14px",
		'price-size'		=> "14px",
		'mileage-size'		=> "14px",
		'discount-size'		=> "14px",
		'title-justify'		=> "center",
		'shortdesc-justify' => "center",
		'price-justify'		=> "center",
		'mileage-justify'	=> "center",
		'discount-justify'	=> "center",
		'title-bold'		=> "normal",
		'shortdesc-bold'	=> "normal",
		'price-bold'		=> "normal",
		'mileage-bold'		=> "normal",
		'discount-bold'		=> "normal"
	);

	$general_option = serialize($general_option);

	update_option("kingkongcart-display",$general_option);

/// 상품 게시판 설정 //////////////////////////////////////////////////

  $board_config = array(
    'afternote' => 'T',
    'qna'   => 'T',
    'vote'    => 'T',
    'private' => 'T',
    'line'    => 20,
    'skin'    => 'modern_white'
  );

  $board_config = serialize($board_config);

  update_option("kingkongcart_board_config",$board_config);


/// 프리픽스 및 재고관리 재고수량 설정 //////////////////////////////////////

  $product_prefix = "TB";
  $order_prefix   = "RD";

  update_option("kingkongcart_product_prefix", $product_prefix);
  update_option("kingkongcart_order_prefix", $order_prefix);

  $auto_quantity = "T";
  $safe_quantity = 10;

  update_option("kingkongcart_auto_quantity", $auto_quantity);
  update_option("kingkongcart_safe_quantity", $safe_quantity);

  
/// 쿠폰설정         //////////////////////////////////////////////////  

/// 적립금 설정       //////////////////////////////////////////////////

  $mileage_config = array(
    'mileage_use'     => 'T',
    'join_mileage'    => 0,
    'afternote_mileage' => 0,
    'min_mileage'   => 100,
    'max_mileage'   => 5000
  );

  $mileage_config = serialize($mileage_config);

  update_option("mileage_config",$mileage_config);

/// 배송 설정         /////////////////////////////////////////////////////

    $shipping = array();
    $shipping['basic']    = 2500;
    $shipping['free']   = 50000;
    $shipping['company']  = "-1";

    $shipping = serialize($shipping);

    update_option("kingkong_shipping", $shipping);  

/// 결제모듈설정       /////////////////////////////////////////////////////


  $payment = array();
  $payment['method'] = 'INICIS';
  $payment['yd_check'] = 0;
  $payment['site_code'] = "";
  $payment['site_key'] = "";
  $payment['paykind'][0] = 'Card';

  $payment = serialize($payment);

  update_option("kingkong_payment", $payment);

}



/* 플러그인 Active 하면 커스텀 페이지 와 DB 테이블을 자동 생성 *********************************************/


register_activation_hook( __FILE__, 'check_kingkongcart_active_create_db' );

function check_kingkongcart_active_create_db() {

    global $wpdb;

    //create the name of the table including the wordpress prefix (wp_ etc)
    $order_table = $wpdb->prefix . "kingkong_order";
    //$wpdb->show_errors(); 
    
    //check if there are any tables of that name already
    if($wpdb->get_var("show tables like '$order_table'") !== $order_table) 
    {
        //create your sql
        $sql =  "CREATE TABLE ". $order_table . " (
                      ID mediumint(12) NOT NULL AUTO_INCREMENT,
                      pid VARCHAR(20) NOT NULL,
                      status mediumint(9),
                      kind VARCHAR (20) NOT NULL, 
                      pname text(20) NOT NULL,
                      order_id VARCHAR(20) NOT NULL,
                      order_price int NOT NULL,
                      receive_name text NOT NULL,
                      receive_contact VARCHAR(20) NOT NULL,
                      address_doro TEXT NOT NULL,
                      address_jibun TEXT NOT NULL,
                      address_detail TEXT NOT NULL,
                      mktime VARCHAR(2) NOT NULL,
                      order_date VARCHAR(20) NOT NULL,
                      UNIQUE KEY ID (ID));";
    }
    
    //include the wordpress db functions
    require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
    dbDelta($sql);
    
    //register the new table with the wpdb object
    if (!isset($wpdb->stats)) 
    {
        $wpdb->stats = $order_table; 
        //add the shortcut so you can use $wpdb->stats
        $wpdb->tables[] = str_replace($wpdb->prefix, '', $order_table); 
    }
}

register_activation_hook( __FILE__, 'check_kingkongcart_active_create_db_meta' );

function check_kingkongcart_active_create_db_meta() {

    global $wpdb;

    //create the name of the table including the wordpress prefix (wp_ etc)
    $meta_table = $wpdb->prefix . "kingkong_order_meta";
    //$wpdb->show_errors(); 
    
    //check if there are any tables of that name already
    if($wpdb->get_var("show tables like '$meta_table'") !== $meta_table) 
    {
        //create your sql
        $sql =  "CREATE TABLE ". $meta_table . " (
                      meta_id bigint(20) NOT NULL AUTO_INCREMENT,
                      order_id bigint(20) NOT NULL,
                      meta_key VARCHAR(255),
                      meta_value longtext,
                      UNIQUE KEY meta_id (meta_id));";
    }
    
    //include the wordpress db functions
    require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
    dbDelta($sql);
    
    //register the new table with the wpdb object
    if (!isset($wpdb->stats)) 
    {
        $wpdb->stats = $meta_table; 
        //add the shortcut so you can use $wpdb->stats
        $wpdb->tables[] = str_replace($wpdb->prefix, '', $meta_table); 
    }

}



register_activation_hook( __FILE__, 'kingkongcart_create_board_db' );

function kingkongcart_create_board_db() {

    global $wpdb;

    //create the name of the table including the wordpress prefix (wp_ etc)
    $order_table = $wpdb->prefix . "kingkong_board";
    //$wpdb->show_errors(); 
    
    //check if there are any tables of that name already
    if($wpdb->get_var("show tables like '$order_table'") !== $order_table) 
    {
        //create your sql
        $sql =  "CREATE TABLE ". $order_table . " (
                      ID mediumint(12) NOT NULL AUTO_INCREMENT,
                      pid mediumint(12) NOT NULL,
                      kind VARCHAR (20) NOT NULL,
                      type VARCHAR (20) NOT NULL,
                      title VARCHAR (200) NOT NULL, 
                      content text NOT NULL,
                      writer text NOT NULL,
                      user mediumint(12) NOT NULL,
                      date VARCHAR(20) NOT NULL,
                      UNIQUE KEY ID (ID));";
    }
    
    //include the wordpress db functions
    require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
    dbDelta($sql);
    
    //register the new table with the wpdb object
    if (!isset($wpdb->stats)) 
    {
        $wpdb->stats = $order_table; 
        //add the shortcut so you can use $wpdb->stats
        $wpdb->tables[] = str_replace($wpdb->prefix, '', $order_table); 
    }
}



register_activation_hook( __FILE__, 'kingkongcart_create_board_meta' );

function kingkongcart_create_board_meta() {

    global $wpdb;

    //create the name of the table including the wordpress prefix (wp_ etc)
    $meta_table = $wpdb->prefix . "kingkong_board_meta";
    //$wpdb->show_errors(); 
    
    //check if there are any tables of that name already
    if($wpdb->get_var("show tables like '$meta_table'") !== $meta_table) 
    {
        //create your sql
        $sql =  "CREATE TABLE ". $meta_table . " (
                      meta_id bigint(20) NOT NULL AUTO_INCREMENT,
                      order_id bigint(20) NOT NULL,
                      meta_key VARCHAR(255),
                      meta_value longtext,
                      UNIQUE KEY meta_id (meta_id));";
    }
    
    //include the wordpress db functions
    require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
    dbDelta($sql);
    
    //register the new table with the wpdb object
    if (!isset($wpdb->stats)) 
    {
        $wpdb->stats = $meta_table; 
        //add the shortcut so you can use $wpdb->stats
        $wpdb->tables[] = str_replace($wpdb->prefix, '', $meta_table); 
    }

}



register_activation_hook( __FILE__, 'check_kingkongcart_active_mypage' );

function check_kingkongcart_active_mypage() {
    global $wpdb; // Not sure if you need this, maybe

    $page_mypage = array(
      'post_title'    => '킹콩카트-마이페이지',
      'post_content'  => '[my_page]',
      'post_status'   => 'publish',
      'post_type'     => 'page',
      'post_author'   => 1
    );


    $page_exists = get_page_by_title( $page_mypage['post_title'] );

    if( $page_exists == null ) {
        $insert = wp_insert_post( $page_mypage );
        if($insert){
          update_option("kingkongcart_mypage_mypage",$insert);
        }
    }

    $page_cart = array(
      'post_title'    => '킹콩카트-장바구니',
      'post_content'  => '[my_page page=cart]',
      'post_status'   => 'publish',
      'post_type'     => 'page',
      'post_author'   => 1
    );


    $page_exists = get_page_by_title( $page_cart['post_title'] );

    if( $page_exists == null ) {
        $insert = wp_insert_post( $page_cart );
        if($insert){
          update_option("kingkongcart_mypage_cart",$insert);
        }
    }

    $page_main = array(
      'post_title'    => '킹콩카트-메인페이지',
      'post_content'  => '[kingkong_product_loop]',
      'post_status'   => 'publish',
      'post_type'     => 'page',
      'post_author'   => 1
    );


    $page_exists = get_page_by_title( $page_main['post_title'] );

    if( $page_exists == null ) {
        $insert = wp_insert_post( $page_main );
        if($insert){
          update_option("kingkongcart_main",$insert);
        }
    }

    $page_wish = array(
      'post_title'    => '킹콩카트-위시리스트',
      'post_content'  => '[my_page page=wish]',
      'post_status'   => 'publish',
      'post_type'     => 'page',
      'post_author'   => 1
    );


    $page_exists = get_page_by_title( $page_wish['post_title'] );

    if( $page_exists == null ) {
        $insert = wp_insert_post( $page_wish );
        if($insert){
          update_option("kingkongcart_mypage_wish",$insert);
        }
    }

    $page_order = array(
      'post_title'    => '킹콩카트-주문내역',
      'post_content'  => '[my_page page=order]',
      'post_status'   => 'publish',
      'post_type'     => 'page',
      'post_author'   => 1
    );


    $page_exists = get_page_by_title( $page_order['post_title'] );

    if( $page_exists == null ) {
        $insert = wp_insert_post( $page_order );
        if($insert){
          update_option("kingkongcart_mypage_order",$insert);
        }
    }



    $page_join = array(
      'post_title'    => '킹콩카트-회원가입',
      'post_content'  => '[kingkong_join_member]',
      'post_status'   => 'publish',
      'post_type'     => 'page',
      'post_author'   => 1
    );


    $page_exists = get_page_by_title( $page_join['post_title'] );

    if( $page_exists == null ) {
        $insert = wp_insert_post( $page_join );
        if($insert){
          update_option("kingkongcart_join_member",$insert);
        }
    }



    $page_profile = array(
      'post_title'    => '킹콩카트-내정보수정',
      'post_content'  => '[my_page page=profile]',
      'post_status'   => 'publish',
      'post_type'     => 'page',
      'post_author'   => 1
    );


    $page_exists = get_page_by_title( $page_profile['post_title'] );

    if( $page_exists == null ) {
        $insert = wp_insert_post( $page_profile );
        if($insert){
          update_option("kingkongcart_mypage_profile",$insert);
        }
    }

    $page_broke = array(
      'post_title'    => '킹콩카트-회원탈퇴',
      'post_content'  => '[my_page page=broke]',
      'post_status'   => 'publish',
      'post_type'     => 'page',
      'post_author'   => 1
    );


    $page_exists = get_page_by_title( $page_broke['post_title'] );

    if( $page_exists == null ) {
        $insert = wp_insert_post( $page_broke );
        if($insert){
          update_option("kingkongcart_mypage_broke",$insert);
        }
    }

    $page_all_order = array(
      'post_title'    => '킹콩카트-구매하기',
      'post_content'  => '[kingkongcart_order]',
      'post_status'   => 'publish',
      'post_type'     => 'page',
      'post_author'   => 1
    );


    $page_exists = get_page_by_title( $page_all_order['post_title'] );

    if( $page_exists == null ) {
        $insert = wp_insert_post( $page_all_order );
        if($insert){
          update_option("kingkongcart_order_page",$insert);
        }
    }

    $page_login = array(
      'post_title'    => '킹콩카트-로그인',
      'post_content'  => '[kingkongcart_login]',
      'post_status'   => 'publish',
      'post_type'     => 'page',
      'post_author'   => 1
    );


    $page_exists = get_page_by_title( $page_login['post_title'] );

    if( $page_exists == null ) {
        $insert = wp_insert_post( $page_login );
        if($insert){
          update_option("kingkongcart_login",$insert);
        }
    }

    $page_policy = array(
      'post_title'    => '킹콩카트-이용약관',
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_type'     => 'page',
      'post_author'   => 1
    );


    $page_exists = get_page_by_title( $page_policy['post_title'] );

    if( $page_exists == null ) {
        $insert = wp_insert_post( $page_policy );
        if($insert){
          update_option("kingkongcart_policy",$insert);
        }
    }


    $page_privacy = array(
      'post_title'    => '킹콩카트-개인정보취급방침',
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_type'     => 'page',
      'post_author'   => 1
    );


    $page_exists = get_page_by_title( $page_privacy['post_title'] );

    if( $page_exists == null ) {
        $insert = wp_insert_post( $page_privacy );
        if($insert){
          update_option("kingkongcart_privacy",$insert);
        }
    }


}



add_filter("change_currency_text", "currency_change");

function currency_change(){
	return "원";
}