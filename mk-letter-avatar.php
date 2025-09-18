<?php
/**
 * Plugin Name: mk-letter-avatar
 * Plugin URI: https://github.com/mengkunsoft/mk-letter-avatar
 * Description: 孟坤字母头像插件，如果评论者没有设置 Gravatar 头像，自动使用昵称首个字符作为头像
 * Version: 1.1.0
 * Author: mengkun
 * Author URI: https://mkblog.cn/
 */

if(!function_exists('mk_letter_avatar')) {
    
    /**
     * 用于生成头像的颜色列表，你可以任意增删改
     */
    $AVATAR_COLORS = array(
        '#1abc9c', '#2ecc71', '#3498db', '#9b59b6', '#34495e', '#16a085', '#27ae60', '#2980b9', '#8e44ad', '#2c3e50', 
        '#f1c40f', '#e67e22', '#e74c3c', '#00bcd4', '#95a5a6', '#f39c12', '#d35400', '#c0392b', '#bdc3c7', '#7f8c8d'
    );
    
    /**
     * 挂载字母头像替换
     */
    function mk_letter_avatar($avatar, $id_or_email = '', $size = '40', $default = '', $alt = '') { 
        // 匹配出原始头像中的信息
        $src = mk_get_html_tag_attribute($avatar, 'src');
        if(!$src) return $avatar;
        
        $alt = mk_get_avatar_name($id_or_email);
        $alt = $alt? $alt: mk_get_html_tag_attribute($avatar, 'alt', $alt);
        if(!$alt) return $avatar;
        
        // 去除原始头像链接中的默认头像，改为输出 404
        $src = htmlspecialchars_decode($src);
        $src = preg_replace('/[\?\&]d[^&]+/is', '', $src);
        $src = $src.'&d=404';
        $src = htmlspecialchars($src);
        
        $class = mk_get_html_tag_attribute($avatar, 'class', 'avatar avatar-'.$size.' photo');
        $title = mk_get_html_tag_attribute($avatar, 'title', $alt);
        
        $avatar = '<img src="'.$src.'" alt="'.$alt.'" title="'.$title.'" class="'.$class.' mk-letter-avatar" height="'.$size.'" width="'.$size.'" onerror="onerror=null;src=\'\';src=mkLetterAvatar(alt,'.$size.')" />';
        return $avatar;
    }
    global $pagenow;
    if(!is_admin() || $pagenow != 'options-discussion.php') {    // 排除后台“讨论设置”的头像
        add_filter('get_avatar', 'mk_letter_avatar', 999999, 5);
	}
    
    /**
     * 取出头像对应的用户名
     */ 
    function mk_get_avatar_name($id_or_email) {
        if(have_comments()) {
            return get_comment_author();
        }
        
        $user = null;
        if(empty($id_or_email)) {
            return null;
        } else if(is_object($id_or_email)) {
            if(!empty($id_or_email->comment_author)) {
                return $id_or_email->comment_author;
            } else if(!empty($id_or_email->user_id)) {
                $id = (int) $id_or_email->user_id;
                $user = get_user_by('id', $id);
            }
        } else if(is_numeric($id_or_email)) { // 是数字，尝试获取该 ID 对应的用户名
            $id = (int) $id_or_email;
            $user = get_user_by('id', $id);
        } else if(is_string($id_or_email)) {
            if (!filter_var($id_or_email, FILTER_VALIDATE_EMAIL)) { // 不是邮箱，当做用户名
                return $id_or_email;
            } else {
                $user = get_user_by('email', $id_or_email);
            }
        }
        // 尝试从用户对象中取出用户名
        if(!empty($user) && is_object($user)) {
            return $user->display_name;
        }
        return null;
    }
    
    
    /**
     * 挂载字母头像生成 js 文件
     */
    function mk_letter_avatar_js() {
        global $AVATAR_COLORS;
        /**
         * LetterAvatar   https://github.com/daolavi/LetterAvatar
         * 
         * Artur Heinze
         * Create Letter avatar based on Initials
         * based on https://gist.github.com/leecrossley/6027780
         */
        ?><style>.mk-letter-avatar[src=""]{visibility: hidden;}</style><script>(function(a,b){window.mkLetterAvatar=function(d,l,j){d=d||"";l=l||60;var h="<?php echo implode(' ', $AVATAR_COLORS); ?>".split(" "),f,c,k,g,e,i;f=String(d);f=f.replace(/\uD83C[\uDF00-\uDFFF]|\uD83D[\uDC00-\uDE4F]/g,"");f=f?f.charAt(0):"?";if(a.devicePixelRatio){l=(l*a.devicePixelRatio)}c=(f=="?"?72:f.charCodeAt(0))-64;k=c%h.length;g=b.createElement("canvas");g.width=l;g.height=l;e=g.getContext("2d");e.fillStyle=j?j:h[k-1];e.fillRect(0,0,g.width,g.height);e.font=Math.round(g.width/2)+"px 'Microsoft Yahei'";e.textAlign="center";e.fillStyle="#fff";e.fillText(f,l/2,l/1.5);i=g.toDataURL();g=null;return i}})(window,document);</script><?php
    }
    add_action('wp_head',    'mk_letter_avatar_js');
    add_action('admin_head', 'mk_letter_avatar_js');
    
    /**
     * 获取 Html 标签内的指定属性值
     * 
     * @param $html      原始 html 标签代码
     * @param $attribute 要获取的标签
     * @param $default   如果没有获取到，返回的默认值
     * @return result
     */
    function mk_get_html_tag_attribute($html, $attribute, $default = '') {
        if(preg_match('/'.$attribute.'\s*=\s*[\"\']([^\"\']*)[\"\']/isU', $html, $result)) {
            if(isset($result[1])) return $result[1];
        }
        return $default;
    }
}
