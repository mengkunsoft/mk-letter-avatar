<?php
/**
 * Plugin Name: mk-letter-avatar
 * Plugin URI: https://mkblog.cn/
 * Description: 孟坤字母头像插件，如果评论者没有设置 Gravatar 头像，自动使用昵称首个字符作为头像
 * Version: 1.0.1
 * Author: mengkun
 * Author URI: https://mkblog.cn/
 */

if(!function_exists('mk_letter_avatar')) {
    
    /**
     * 生成头像的颜色列表
     * 
     * @notice 这里的颜色值 *必须* 是 20 个！
     */
    $AVATAR_COLORS = array(
        '#1abc9c', '#2ecc71', '#3498db', '#9b59b6', '#34495e', '#16a085', '#27ae60', '#2980b9', '#8e44ad', '#2c3e50', 
        '#f1c40f', '#e67e22', '#e74c3c', '#00bcd4', '#95a5a6', '#f39c12', '#d35400', '#c0392b', '#bdc3c7', '#7f8c8d'
        );
    
    /**
     * 挂载字母头像替换
     */
    function mk_letter_avatar( $avatar, $id_or_email = '', $size = '40', $default = '', $alt = '' ) { 
        // 匹配出原始头像中的信息
        $src   = mk_get_html_tag_attribute( $avatar, 'src' );
        if(!$src) return $avatar;
        
        $alt   = mk_get_html_tag_attribute( $avatar, 'alt',   $alt );
        if(!$alt) return $avatar;
        
        // 去除原始头像链接中的默认头像，改为输出 404
        $src   = preg_replace('/[\?\&]d[^&]+/is', '&d=404', $src);
        
        $class = mk_get_html_tag_attribute( $avatar, 'class', 'avatar avatar-'.$size.' photo' );
        $title = mk_get_html_tag_attribute( $avatar, 'title', $alt );
        
        $avatar = '<img src="'.$src.'" alt="'.$alt.'" title="'.$title.'" class="'.$class.' mk-letter-avatar" height="'.$size.'" width="'.$size.'" onerror="onerror=null;src=\'\';src=mkLetterAvatar(alt,'.$size.')" />';
        return $avatar;
    }
    add_filter( 'get_avatar', 'mk_letter_avatar', 999999, 5 );
    
    /**
     * 挂载字母头像生成 js 文件
     */
    function mk_letter_avatar_js() {
        global $AVATAR_COLORS;
        /*
         * LetterAvatar   https://github.com/daolavi/LetterAvatar
         * 
         * Artur Heinze
         * Create Letter avatar based on Initials
         * based on https://gist.github.com/leecrossley/6027780
         */
        ?><style>.mk-letter-avatar[src=""]{visibility: hidden;}</style><script>(function(e,g){window.mkLetterAvatar=function(a,b,f){b=b||60;var h="<?php echo implode(' ', $AVATAR_COLORS); ?>".split(" ");a=String(a||"").split(" ");a=1==a.length?a[0]?a[0].charAt(0):"?":a[0].charAt(0)+a[1].charAt(0);e.devicePixelRatio&&(b*=e.devicePixelRatio);var k=(("?"==a?72:a.charCodeAt(0))-64)%20;var c=g.createElement("canvas");c.width=b;c.height=b;var d=c.getContext("2d");d.fillStyle=
f?f:h[k-1];d.fillRect(0,0,c.width,c.height);d.font=Math.round(c.width/2)+"px 'Microsoft Yahei'";d.textAlign="center";d.fillStyle="#FFF";d.fillText(a,b/2,b/1.5);return c.toDataURL()}})(window,document);</script><?php
    }
    add_action( 'wp_head', 'mk_letter_avatar_js' );
    
    /**
     * 获取 Html 标签内的指定属性值
     * 
     * @param $html      原始 html 标签代码
     * @param $attribute 要获取的标签
     * @param $default   如果没有获取到，返回的默认值
     * @return result
     */
    function mk_get_html_tag_attribute( $html, $attribute, $default = '' ) {
        if(preg_match('/'.$attribute.'\s*=\s*[\"\']([^\"\']*)[\"\']/isU', $html, $result)) {
            if(isset($result[1])) return $result[1];
        }
        return $default;
    }
}