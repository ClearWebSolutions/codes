{if $module->installed}
	<div class="success-box" id="moduleSuccess"><div class="success">Module Store is already installed and attached to <b>{$module->page->name}.php</b>. See <b>{$module->page->name}.php</b> to modify this module.</div></div>
{else}
	{if $module->id}
		<h1>Update module Store</h1>
	{/if}
	<div class="success-box hidden" id="moduleSuccess"><div class="success">Module Store successfully {if $module->id}updated{else}added{/if}!</div></div>

	<form action="module.php" id="{if $module->id}edit{else}add{/if}module" method="post">

		{if !$module->id}
			<p><i>This module would create a shopping cart that would be present on each page.<br/>
			An ability to edit the stock of the products, orders management and PayPal integration(both express and regular checkout).<br/>
			To avoid code duplication it's good to apply this module to the same page where your products(Complex Object) module is installed.<br/>
			Module would be applied to the <span id="selectedpage"></span> page.</i></p>
			<p><i>PayPal access details are in the class/base/ShoppingCartBase.php, by default they are empty and PayPal class applies the test API and details.</i></p>
		{/if}

		<div class="error-box hidden" id="moduleError"><div class="error"></div></div>

		<input type="hidden" name="action" value="{if $module->id}edit{else}add{/if}"/>
		<input type="hidden" name="moduleid" value="{$module->id}"/>
		<input type="hidden" name="page" id="page"/>
		<input type="hidden" name="module" value="store"/>
		<input type="hidden" name="warning" id="warning" value=""/>

		<div class="rows">
			<table class="form">
				<tr>
					<td>Products object:</td>
					<td width="200">
						<select name="products">
							<option></option>
							{section name=i loop=$site->modules|@count}
								{if $site->modules[i].module=='complexObject'}
									<option value="{$site->modules[i].m->tbl}" {if $module->id&&$module->products_module}selected="selected"{/if}>{$site->modules[i].title}</option>
								{/if}
							{/section}
						</select>
					</td>
					<td><i>products could be any Complex Object module you have already installed</i></td>
				</tr>
				<tr>
					<td>Checkout page template:</td>
					<td width="200">
						<select name="checkout">
							<option></option>
							{section name=i loop=$site->templates|@count}
								<option value="{$site->templates[i]}" {if $module->id&&$module->checkout}selected="selected"{/if}>{$site->templates[i]}</option>
							{/section}
						</select>
					</td>
					<td></td>
				</tr>

			</table>



			<h3 class="pl40">Product options (like size, color etc.), leave blank if no options. Quantity is not an option, it's already included in the module.</h3>
			<div class="properties optionsTable">
				<input type="hidden" name="ttl" value="{if $module->id}{$module->request.ttl}{else}1{/if}"/>
				<table cellpadding="0" cellspacing="0" id="fieldsTable">
					{if $module->id}
					{section name=i start=1 loop=$module->request.ttl+1}
					<tr>
						<td width="20%">
							<label>Option title</label>
							{assign var=index value="title"|cat:$smarty.section.i.index}
							<input type="text" name="title{$smarty.section.i.index}" class="fieldtitle" value="{$module->request[$index]}"/><br/>
						</td>
						<td>
							<label>Input type</label>
							{assign var=tindex value="type"|cat:$smarty.section.i.index}
							<select name="type{$smarty.section.i.index}" class="inputType">
								<option value="text" {if $module->request[$tindex]=='text'}selected="selected"{/if}>text</option>
								<option value="select" {if $module->request[$tindex]=='select'}selected="selected"{/if}>select</option>
							</select>
						</td>
						<td>
							{if $module->request[$tindex]=='select'}
								{assign var=oindex value="optionsttl"|cat:$smarty.section.i.index}
								<span class="optionsTitle options"><input type="hidden" name="optionsttl{$smarty.section.i.index}" value="{$module->request[$oindex]}" class="optionsttl"/>Select Options:
								{section name=j loop=$module->request[$oindex]}
									<a class="deleteSmall"></a>
								{/section}
								</span>
							{/if}
						</td>
						<td>
							{if $module->request[$tindex]=='select'}
							<span class="options">
								{section name=j start=1 loop=$module->request[$oindex]+1}
								{assign var=index value="optionvalue"|cat:$smarty.section.i.index}
								{assign var=index value=$index|cat:'_'}
								{assign var=index value=$index|cat:$smarty.section.j.index}
								<input type="text" placeholder="option value" class="mt5" name="optionvalue{$smarty.section.i.index}_{$smarty.section.j.index}" value="{$module->request[$index]}"/>
								{/section}
								<br/><a href="javascript:" class="addOneMore">Add One More Option</a>
							</span>
							{/if}
						</td>
						<td class="fs10" width="100px">
							<a class="deleteSmall"></a>
						</td>
					</tr>
					{/section}
					{else}
					<tr>
						<td width="20%">
							<label>Option title</label>
							<input type="text" name="title1" class="fieldtitle"/><br/>
						</td>
						<td width="100">
							<label>Input type</label>
							<select name="type1" class="inputType">
								<option value="text">text</option>
								<option value="select">select</option>
							</select>
						</td>
						<td></td>
						<td></td>
						<td class="fs10" width="100px">
							<a class="deleteSmall"></a>
						</td>
					</tr>
					{/if}
				</table>
				<br/>
				<a href="javascript:" class="btnGrey addField">Add One More Option</a>
				<br/><br/>
		</div>




		</div>

		<div class="submitLine">
			<a href="javascript:" class="btnGreen">{if $module->id}Update{else}Add{/if} Module</a>
		</div>

	</form>
{/if}