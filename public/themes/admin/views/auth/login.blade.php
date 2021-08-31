

<div class="login layui-anim layui-anim-up">
	
	<div class="login-con">
		<div class="login-left">
			<p>源心再生小程序管理后台</p>
			<span>探索干细胞技术 · 解码健康信息</span>
		</div>
		<div class="login-right">
        {!! Theme::partial('message') !!}
		<div class="login-con-title">
			
			
		</div>
		{!!Form::vertical_open()->id('login')->method('POST')->class('layui-form')->action(guard_url('login')) !!}

		<input name="email" placeholder="账号"  type="text" lay-verify="required"  class="layui-input" >
		<input name="password" placeholder="密码"  type="password" lay-verify="pass" class="layui-input">
		<div class="login_btn-box">
			<input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit" class="login_btn">
		</div>
		
		<input id="rememberme" type="hidden" name="rememberme" value="1">
		{!!Form::Close()!!}
		</div>
	</div>
</div>