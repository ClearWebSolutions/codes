<div class="menu jqueryslidemenu" id="menu">
<ul>
		<li {if $menu=='account'||$menu=='metadata'}class="selected1"{/if}><a href="account.php">Settings<br/><div class="tri"></div></a>
			<ul>
				<li><a href="account.php">Account</a></li>
				<li><a href="metadata.php">Metadata</a></li>
			</ul>
		</li>
</ul>
</div>

<div class="user">{$admin.username}, <a href="logout.php" class="logout"></a></div>