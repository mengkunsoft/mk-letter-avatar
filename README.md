mk-letter-avatar
========

![](https://user-images.githubusercontent.com/16880885/75250417-498fb880-5813-11ea-96ed-1ba158e9e67e.png)

[![GitHub stars](https://img.shields.io/github/stars/mengkunsoft/mk-letter-avatar.svg)](https://github.com/mengkunsoft/mk-letter-avatar/stargazers) [![GitHub forks](https://img.shields.io/github/forks/mengkunsoft/mk-letter-avatar.svg)](https://github.com/mengkunsoft/mk-letter-avatar/network) [![GitHub issues](https://img.shields.io/github/issues/mengkunsoft/mk-letter-avatar.svg)](https://github.com/mengkunsoft/mk-letter-avatar/issues) [![GitHub license](https://img.shields.io/github/license/mengkunsoft/mk-letter-avatar.svg)](https://github.com/mengkunsoft/mk-letter-avatar/blob/master/LICENSE)

mk-letter-avatar 是一个简单好用的 WordPress 字母头像插件。

WordPress 默认采用的是 Gravatar 头像，但很多人可能没有设置 Gravatar 头像，因而只能显示出默认的头像。本插件就是将无头像用户的头像显示成动态生成的`昵称首字符`头像。

说到字母头像，其实之前已有多种方案。本插件与其它同类插件不同的是字母头像的生成完全是在`前端`实现的，而且支持中文。整个插件大小只有 5kb 左右。插件启用后不会在服务器产生任何缓存文件，纯净好用！

本插件无需任何设置，直接在 WordPress 后台上传并启用即可。

插件的头像生成代码基于 https://github.com/daolavi/LetterAvatar 和 https://gist.github.com/leecrossley/6027780

### 在线演示
-----

参考 [孟坤博客](https://mkblog.cn) 的评论区头像

### 常见问题
-----

#### 启用插件后不生效

请检查所用的主题是否使用的 WordPress 自带的评论模板或者使用 WordPress 默认函数 `get_avatar()` 获取头像，如若不是，请改用 `get_avatar()`。

#### 启用插件后控制台多了很多 404 报错

因为插件需要根据 Gravatar 返回的状态来判断是否有头像，所以会产生这样的问题。除此之外，无任何“毒副作用”。如果觉得看着难受，可以在页面加载完成后使用 `console.clear();` 清除控制台的错误输出。

### 相关推荐
-----
[使用 LetterAvatar 实现纯前端生成字母头像](https://mkblog.cn/1886/)

[mk-sitemap 站点地图插件](https://github.com/mengkunsoft/mk-sitemap)

[mkBlog 简约主题](https://mkblog.cn/theme-mkblog/)

### 更新日志
-----

#### v1.1.0 `2020/2/25`
- 优化代码，适配更多主题
- 支持在后台也显示字母头像

#### v1.0.4 `2019/4/27`
- 修改为仅绘制一个字符作为头像
- 过滤 emoji

#### v1.0.3 `2019/4/14`
- 修复头像链接被 `htmlspecialchars` 后无效的 bug（感谢 @sy-records 提交反馈）

#### v1.0.2 `2019/4/5`
- 不限制生成头像的颜色列表元素个数

#### v1.0.1 `2019/3/25`
- 如果没有 alt 信息，直接返回默认头像

#### v1.0.0 `2019/3/24`
- 横空出世！