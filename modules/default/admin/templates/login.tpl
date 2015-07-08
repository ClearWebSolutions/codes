{include file="header.tpl"}

<div class="login">
	<div class="q">
		<h1>Log In</h1>
		<a href="javascript:" id="forgotAccess">Forgot access details?</a>
	</div>


	<div id="login" class="box loginBox">
		<form action="" method="post" id="loginFrm">
		<div class="tri"></div>
		<div class="prl30"><br/>
			<span class="error {if !$error}hidden{/if}" id="loginErr">{$error}</span>
			<div class="row">
				<label>Username</label>
				<input type="text" id="username" name="username"/>
			</div>
			<div class="row">
				<label>Password</label>
				<input type="password" id="password" name="password"/>
			</div>
			<div class="rm"><input type="checkbox" name="rememberme" value="1" class="cb"/> Remember me</div>
		</div>
		<div class="submitLine">
				<a href="javascript:" id="loginBtn" class="btnGreen mr20">Submit</a>
		</div>
		<input type="hidden" name="sbm" value="1"/>
		</form>
	</div>


	<div id="forgotPassForm" class="box loginBox hidden">
		<form action="" method="post" id="forgotFrm">
		<div class="tri"></div>
		<div class="success hidden">
			Your access details has been emailed to you.
		</div>
		<div class="form">
			<div class="prl30 pt20">
				<p id="message">Please enter your email below and we'll send them to you.</p>
				<p id="forgotError" class="error hidden">There is no such email in system.</p>
				<div class="row">
					<label>Email</label><input type="text" name="email" id="email"/>
				</div>
			</div>	
			<div class="submitLine">
				<a href="javascript:" id="forgotBtn" class="btnGreen">Submit</a>
				<a href="javascript:" class="btnCancel">Cancel</a>
			</div>
		</div>
		</form>
	</div>


</div>

{include file="footer.tpl"}