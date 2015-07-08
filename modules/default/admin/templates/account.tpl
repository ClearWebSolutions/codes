{include file="header.tpl"}

		<h1>Account</h1>

		<div class="outerRow {if !$success&&!$error}hidden{/if}">
			<div class="success-box {if !$success}hidden{/if}">
				<div class="success">Account updated!</div>
			</div>
			<div class="error-box {if !$error}hidden{/if}">
				<div class="error">{$error}</div>
			</div>
		</div>

		<form action="account.php" method="post" id="accountfrm">
		<input type="hidden" name="sbm" value="1"/>
		<div class="cnt account">
				<div class="frmRow">
					<label>Username <span class="error">*</span></label>
					<div class="input">
						<input type="text" name="username"/>
					</div>
				</div>
				<div class="frmRow even">
					<label>Password <span class="error">*</span></label>
					<div class="input">
						<input type="password" name="password"/>
					</div>
				</div>
				<div class="frmRow">
					<label>New Username</label>
					<div class="input">
						<input type="text" name="new_username"/> <i class="desc">please fill this only if you'd like to change the username</i>
					</div>
				</div>
				<div class="frmRow even">
					<label>New Password</label>
					<div class="input">
						<input type="password" name="new_password"/> <i class="desc">please fill this only if you'd like to change the current password</i>
					</div>
				</div>
				<div class="frmRow">
					<label>Repeat New Password</label>
					<div class="input">
						<input type="password" name="new_password1"/>
					</div>
				</div>
				<div class="frmRow even">
					<label>Email</label>
					<div class="input">
						<input type="text" name="email" value="{$settings->email}"/>
					</div>
				</div>
		</div>
		</form>
		
		<div class="frmRow account">
			<label class="error">* required fileds</label>
			<div class="input">
				<div class="submitLine">
					<a href="javascript:submitFrm('accountfrm')" class="btnGreen">Update</a>
				</div>
			</div>
		</div>

{include file="footer.tpl"}