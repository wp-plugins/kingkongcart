<?php
 /*
 Plugin Name: COOP Members
 Plugin URI: http://www.wpcoop.kr
 Description: 워드프레스협동조합에서 제공하는 한국형 소셜로그인/회원가입 플러그인 입니다.
 Version: 1.0.0
 Author: Bryan Lee
 Author URI: http://www.wpcoop.kr
 License: GPL2

 /* Copyright 2014 ithemeso (email : bryan@wpcoop.kr)
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
 * @package wpcoop
 * @category Core
 * @author bryan 
 */

define('WPCOOP_ABSPATH', plugin_dir_path(__FILE__));             // 플러그인 절대경로
define('WPCOOP_PLUGINS_URL', plugins_url('', __FILE__));         // 플러그인 상대경로

require_once 'class/class.wpcoop_setup.php';							       // 초기 설정정보 셋팅 클래스
require_once 'class/class.wpcoop_login.php';							       // 로그인 관련 옵션 클래스
require_once 'class/class.wpcoop_joinForm.php';                  // 회원가입폼 관련 클래스
require_once 'class/class.naverOAuth.php';								       // 네이버 로그인 클래스
require_once 'php/dashboard.php';										             // 프레임워크 대시보드
require_once 'php/popup.php';                                    // 팝업 설정
require_once 'php/functions.php';										             // 필요 함수들
require_once 'php/shortcodes/shortcode-login.php';						   // 로그인 페이지 숏코드
require_once 'php/shortcodes/shortcode-join.php';                // 회원가입 페이지 숏코드
require_once 'php/shortcodes/shortcode-modify.php';              // 회원정보 수정 페이지 숏코드
require_once 'php/shortcodes/shortcode-mbroke.php';              // 회원탈퇴 페이지 숏코드
require_once 'php/shortcodes/shortcode-reset-pwd.php';           // 비밀번호 초기화 페이지 숏코드

register_activation_hook( __FILE__, array('wpcoop_setup','wpc_set_options') );