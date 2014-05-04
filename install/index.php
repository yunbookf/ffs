<?php
if(is_file('install.lock')) {
	header('Location: ../');
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="../glob/admin/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="../glob/admin/jquery-ui.min.js"></script>
<script type="text/javascript" src="images/main.js"></script>
<link href="images/style.css" type="text/css" rel="stylesheet" />
<title>FFS 安装向导</title>
</head>
<body>
<div id="no1">分享文件从来没有这么轻松</div>
<div id="no2">FFS 6</div>
<div id="no3">FFS 6 </div>
<div id="no4"></div>
<div id="no5"><a href="../">进入网站&nbsp;&gt;&gt;</a></div>
<div id="license">
    <p>版权所有 &copy;2007 - 2014，<a href="http://www.maiyun.net" target="_blank">迈云网络</a>（http://www.maiyun.net）保留所有权利。</p>
    <p>感谢您选择 快速文件分享系统（以下简称 FFS）。FFS 是一款基于 PHP + 多重数据库的先进的网络文件存储分享系统，通过本系统，用户可以将资料在线上传到您的系统中，独创的文件分享码模式，轻松分享每一个文件。更有丰富多彩的文件应用提升文件的价值。</p>
    <p>迈云网络为 FFS 产品的开发商，依法独立拥有 FFS 产品著作权。迈云网络网址为 http://www.maiyun.net。使用者：无论个人或组织、盈利与否、用途如何（包括以学习和研究为目的），均需仔细阅读本协议，在理解、同意、并遵守本协议的全部条款后，方可开始使用 FFS 软件。</p>
    <p>本授权协议适用且仅适用于 FFS 所有版本，迈云拥有对本授权协议的最终解释权。</p>
      <h3>I. 协议许可的权利</h3>
      <ol>
        <li>您可以在完全遵守本最终用户授权协议的基础上，将本软件应用于非商业用途，而不必支付软件版权授权费用。</li>
        <li>您可以在协议规定的约束和限制范围内修改 FFS 源代码(如果被提供的话)或界面风格以适应您的网站要求。</li>
        <li>您拥有使用本软件构建的文件分享系统中全部会员资料、文章及相关信息的所有权，并独立承担与文章内容的相关法律义务。</li>
        <li>获得商业授权之后，您可以将本软件应用于商业用途，同时依据所购买的授权类型中确定的技术支持期限、技术支持方式和技术支持内容，自购买时刻起，在技术支持期限内拥有通过指定的方式获得指定范围内的技术支持服务。商业授权用户享有反映和提出意见的权力，相关意见将被作为首要考虑，但没有一定被采纳的承诺或保证。</li>
      </ol>
    <h3>II. 协议规定的约束和限制</h3>
    <ol>
      <li>未获商业授权之前，不得将本软件用于商业用途（包括但不限于企业网站、经营性网站、以营利为目或实现盈利的网站）。购买商业授权请登陆http://www.maiyun.net参考相关说明。</li>
      <li>不得对本软件或与之关联的商业授权进行出租、出售、抵押或发放子许可证。</li>
      <li>无论如何，即无论用途如何、是否经过修改或美化、修改程度如何，只要使用 FFS 的整体或任何部分，未经书面许可，页面页脚处的 FFS 名称和迈云下属网站（http://www.maiyun.net） 的链接都必须保留，而不能清除或修改。</li>
      <li>禁止在 FFS 的整体或任何部分基础上以发展任何派生版本、修改版本或第三方版本用于重新分发。</li>
      <li>如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回，并承担相应法律责任。</li>
    </ol>
    <h3>III. 有限担保和免责声明</h3>
      <ol>
      <li>本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。</li>
      <li>用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未购买产品技术服务之前，我们不承诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任。</li>
      <li>迈云不对使用本软件构建的文件分享系统中的文章、软件、程序或信息承担责任。</li>
    </ol>
    <p>有关 FFS 最终用户授权协议、商业授权与技术服务的详细内容，均由 FFS 官方网站独家提供。迈云拥有在不事先通知的情况下，修改授权协议和服务价目表的权力，修改后的协议或价目表对自改变之日起的新授权用户生效。</p>
    <p>电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和等同的法律效力。您一旦开始安装 FFS，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。</p>
    <div id="licenseBtns"><input type="button" value="接受协议" class="button" onclick="goStep2()" />　　　　<input id="licenseCancelBtn" type="button" value="拒绝协议" class="button" /></div>
</div>
<!-- 环境检测 step 2 -->
<div id="step2">
  <div id="step2Txt">
    正在检测安装环境... <br /><span id="step2cur">_</span>
  </div>
</div>
<!-- loading GUI --->
<div id="guiBgLogo"></div>
<div id="progressBar"><div id="progressBarDiv"></div></div>
<!-- windows -->
<div id="window">
  <div id="windowTitle">FFS 安装向导</div>
  <div id="windowContent">
    <div id="step3">
      <strong style="font-size:14px;">创始人信息</strong><br /><br />
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableInfo">
        <tr>
          <td width="100">账户：</td>
          <td width="250"><input name="username" type="text" value="admin" class="textbox" readonly="readonly" /></td>
          <td>最高权限账户登录名</td>
        </tr>
        <tr>
          <td>密码：</td>
          <td><input name="pwd" type="text" class="textbox" /></td>
          <td>最高权限账户登录密码</td>
        </tr>
      </table>
    </div>
    <div id="step4" style="display:none;">
      <div style="font-size:14px;text-align:center;font-weight:bold;">关于《迈云旗下产品改善计划》的说明</div><br />
      <div style="height:260px;overflow-y:scroll;">
        <p style="text-indent:24px;">为了不断改进产品质量，改善用户体验，FFS 《迈云旗下产品改善计划》，该系统有利于我们分析用户在存储的操作习惯，进而帮助我们在未来的版本中对产品进行改进，设计出更符合用户需求的新功能。</p>
        <p style="text-indent:24px;">该系统不会收集站点敏感信息，不收集用户资料，不存在安全风险，并且经过实际测试不会影响程序运行效率。</p>
        <p style="text-indent:24px;">您安装使用本产品表示您同意加入《迈云旗下产品改善计划》，FFS 运营部门会通过对站点的分析为您提供运营指导建议，我们将提示您如何根据站点运行情况开启系统功能，如何进行合理的功能配置，以及提供其他的一些运营经验等。</p>
        <p style="text-indent:24px;">为了方便我们和您沟通运营策略，请您留下常用的网络联系方式：</p>
        <table width="75%" border="0" cellspacing="0" cellpadding="0" class="tableInfo" style="margin:20px auto;">
          <tr>
            <td width="70">QQ：</td>
            <td width="240"><input name="qq" type="text" class="textbox" /></td>
            <td>您的常用 QQ 号</td>
          </tr>
          <tr>
            <td>Email：</td>
            <td><input name="email" type="text" class="textbox" /></td>
            <td>您的常用邮箱</td>
          </tr>
          <tr>
            <td>手机：</td>
            <td><input name="mphone" type="text" class="textbox" /></td>
            <td>您的常用手机号</td>
          </tr>
        </table>
      </div>
    </div>
    <div id="step5" style="display:none;">
      <strong style="font-size:14px;">谢谢</strong><br /><br />
      您还在享受着疯狂点击“下一步”的愉悦感受吗，现在有个坏消息，您只能点击“完成安装”了。<br />
      请您进入网站后台完成一些基本的配置内容。
    </div>
  </div>
  <div id="windowControls"><input id="windowControlNextBtn" type="button" class="button" value="下一步" /></div>
</div>
</body>
</html>