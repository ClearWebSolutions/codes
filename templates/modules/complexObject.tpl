{if $module->installed}
	<div class="success-box" id="moduleSuccess"><div class="success">There is already a Content module installed for this page. Select the Content module from the list on the left to edit it.</div></div>
{else}
	{if $module->id}
		<h1>Update module Complex Object</h1>
		<p><i>Update would cause the loosing of all the data in the database as it only should be done on the development environment with development database!</i></p>
	{/if}
	<div class="success-box hidden" id="moduleSuccess"><div class="success">Module Complex Object successfully {if $module->id}updated{else}added{/if}!</div></div>

	<form action="module.php" id="{if $module->id}edit{else}add{/if}module" method="post">

		{if !$module->id}
			<p><i>This module will create an ability to add/edit/delete the objects with properties defined below. Module would be applied to the <span id="selectedpage"></span> page.</i></p>
		{/if}
	
		<div class="error-box hidden" id="moduleError"><div class="error"></div></div>

		<input type="hidden" name="action" value="{if $module->id}edit{else}add{/if}"/>
		<input type="hidden" name="moduleid" value="{$module->id}"/>
		<input type="hidden" name="page" id="page"/>
		<input type="hidden" name="module" value="complexObject"/>
		<input type="hidden" name="warning" id="warning" value=""/>

		<div class="rows">
			<table class="form">
				<tr>
					<td>Title:</td>
					<td><input type="text" name="title" value="{$module->title}"/></td>
					<td></td>
				</tr>
				<tr>
					<td>DB table:</td>
					<td><input type="text" name="db_tbl" value="{$module->tbl}"/></td>
					<td>{if $module->id}<i>Changing the table name would lead to loosing the previous table and its data!<br/>Also the {$module->tbl|ucfirst}Base.php as well as {$module->tbl|ucfirst}.php classes would be deleted!</i>{/if}</td>
				</tr>
				<tr class="hidden">
					<td>Multilanguage:</td>
					<td><input type="radio" name="multilanguage" value="1" {if $module->id}{if $module->multilanguage==1}checked="checked"{/if}{else}checked="checked"{/if}/> Yes &nbsp; <input type="radio" name="multilanguage" value="0" {if $module->id&&$module->multilanguage==0}checked="checked"{/if}/> No</td>
					<td></td>
				</tr>
			</table>


			<div class="properties">
				<input type="hidden" name="ttl" value="{if $module->id}{$module->request.ttl}{else}1{/if}"/>
				<table cellpadding="0" cellspacing="0" id="fieldsTable">
					{if $module->id}
					{section name=i start=1 loop=$module->request.ttl+1}
					<tr>
						<td width="20%">
							<label>Field title</label>
							{assign var=index value="title"|cat:$smarty.section.i.index}
							<input type="text" name="title{$smarty.section.i.index}" class="fieldtitle" value="{$module->request[$index]}"/><br/>
							{assign var=oindex value="optionsttl"|cat:$smarty.section.i.index}
							{assign var=tindex value="type"|cat:$smarty.section.i.index}
							{if $module->request[$tindex]=='select'||$module->request[$tindex]=='radio'}
							<span class="optionsTitle options"><input type="hidden" name="optionsttl{$smarty.section.i.index}" value="{$module->request[$oindex]}" class="optionsttl"/>Radio Options:
								{section name=j loop=$module->request[$oindex]}
									<a class="deleteSmall"></a>
								{/section}
							</span>
							{/if}
						</td>
						<td>
							<label>Input type</label>
							<select name="type{$smarty.section.i.index}" class="inputType">
								<option value="text" {if $module->request[$tindex]=='text'}selected="selected"{/if}>text</option>
								<option value="date" {if $module->request[$tindex]=='date'}selected="selected"{/if}>date</option>
								<option value="radio" {if $module->request[$tindex]=='radio'}selected="selected"{/if}>radio</option>
								<option value="select" {if $module->request[$tindex]=='select'}selected="selected"{/if}>select</option>
								<option value="textarea" {if $module->request[$tindex]=='textarea'}selected="selected"{/if}>textarea</option>
								<option value="html" {if $module->request[$tindex]=='html'}selected="selected"{/if}>HTML editor</option>
							</select>
							{if $module->request[$tindex]=='select'||$module->request[$tindex]=='radio'}
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
						<td width="20%">
							<label>DB field name</label>
							{assign var=index value="dbfield"|cat:$smarty.section.i.index}
							<input type="text" name="dbfield{$smarty.section.i.index}" class="dbfield" value="{$module->request[$index]}"/>
							{if $module->request[$tindex]=='select'||$module->request[$tindex]=='radio'}
							<span class="options">
								{section name=j start=1 loop=$module->request[$oindex]+1}
								{assign var=index value="optionname"|cat:$smarty.section.i.index}
								{assign var=index value=$index|cat:'_'}
								{assign var=index value=$index|cat:$smarty.section.j.index}
								<input type="text" placeholder="option name" class="mt5" name="optionname{$smarty.section.i.index}_{$smarty.section.j.index}" value="{$module->request[$index]}"/>
								{/section}
							</span>
							{/if}
						</td>
						<td>
							<label>DB type</label>
							<select name="dbtype{$smarty.section.i.index}" class="dbType">
								{assign var=index value="dbtype"|cat:$smarty.section.i.index}
								<option value="varchar" {if $module->request[$index]=='varchar'}selected="selected"{/if}>varchar</option>
								<option value="int" {if $module->request[$index]=='int'}selected="selected"{/if}>int</option>
								<option value="tinyint" {if $module->request[$index]=='tinyint'}selected="selected"{/if}>tinyint</option>
								<option value="double" {if $module->request[$index]=='double'}selected="selected"{/if}>double</option>
								<option value="date" {if $module->request[$index]=='date'}selected="selected"{/if}>date</option>
								<option value="datetime" {if $module->request[$index]=='datetime'}selected="selected"{/if}>datetime</option>
								<option value="timestamp" {if $module->request[$index]=='timestamp'}selected="selected"{/if}>timestamp</option>
								<option value="blob" {if $module->request[$index]=='blob'}selected="selected"{/if}>blob</option>
							</select>
							{if $module->request[$tindex]=='select'||$module->request[$tindex]=='radio'}
							<span class="options">
								{section name=j start=1 loop=$module->request[$oindex]+1}
								{assign var=index value="optiondefault"|cat:$smarty.section.i.index}
								{assign var=index value=$index|cat:'_'}
								{assign var=index value=$index|cat:$smarty.section.j.index}
								<div><input type="checkbox" class="mt5" value="1" name="optiondefault{$smarty.section.i.index}_{$smarty.section.j.index}" {if $module->request[$index]==1}checked="checked"{/if}/> default value</div>
								{/section}
							</span>
							{/if}
						</td>
						<td>
							<label>DB length</label>
							{assign var=index value="dblength"|cat:$smarty.section.i.index}
							<input type="text" value="{$module->request[$index]}" class="smallinput dblength" name="dblength{$smarty.section.i.index}"/>
						</td>
						<td>
							<label>DB default</label>
							{assign var=index value="dbdefault"|cat:$smarty.section.i.index}
							<input type="text" class="smallinput dbdefault" name="dbdefault{$smarty.section.i.index}" value="{$module->request[$index]}"/>
						</td>
						<td class="fs10" width="100px">
							<a class="deleteSmall"></a>
							{assign var=index value="required"|cat:$smarty.section.i.index}
							<input type="checkbox" class="required" name="required{$smarty.section.i.index}" value="1" {if $module->request[$index]==1}checked="checked"{/if}/>Required<br/>
							{assign var=index value="searchable"|cat:$smarty.section.i.index}
							<input type="checkbox" class="searchable" name="searchable{$smarty.section.i.index}" value="1" {if $module->request[$index]==1}checked="checked"{/if}/>Searchable<br/>
							{assign var=index value="admindisplay"|cat:$smarty.section.i.index}
							<input type="checkbox" class="admindisplay" name="admindisplay{$smarty.section.i.index}" value="1" {if $module->request[$index]==1}checked="checked"{/if}/>Admin Display<br/>
							{assign var=index value="multilanguage"|cat:$smarty.section.i.index}
							<span><input type="checkbox" class="multilanguage" name="multilanguage{$smarty.section.i.index}" value="1" {if $module->request[$index]==1}checked="checked"{/if}/>Multi-language</span>
						</td>
					</tr>
					{/section}
					{else}
					<tr>
						<td width="20%">
							<label>Field title</label>
							<input type="text" name="title1" class="fieldtitle"/><br/>
							<!--<span class="optionsTitle options"><input type="hidden" name="optionsttl1" value="1" class="optionsttl"/>Radio Options:<a class="deleteSmall"></a></span>-->
						</td>
						<td>
							<label>Input type</label>
							<select name="type1" class="inputType">
								<option value="text">text</option>
								<option value="date">date</option>
								<option value="radio">radio</option>
								<option value="select">select</option>
								<option value="textarea">textarea</option>
								<option value="html">HTML editor</option>
							</select>
							<!--<span class="options">
								<input type="text" placeholder="option value" class="mt5" name="optionvalue1_1"/>
								<input type="text" placeholder="option value" class="mt5" name="optionvalue1_2"/>
								<br/><a href="#" class="addOneMore">Add One More Option</a>
							</span>-->
						</td>
						<td width="20%">
							<label>DB field name</label>
							<input type="text" name="dbfield1" class="dbfield"/>
							<!--<span class="options">
								<input type="text" placeholder="option name" class="mt5" name="optionname1_1"/>
								<input type="text" placeholder="option name" class="mt5" name="optionname1_2"/>
							</span>-->
						</td>
						<td>
							<label>DB type</label>
							<select name="dbtype1" class="dbType">
								<option value="varchar">varchar</option>
								<option value="int">int</option>
								<option value="tinyint">tinyint</option>
								<option value="double">double</option>
								<option value="date">date</option>
								<option value="datetime">datetime</option>
								<option value="timestamp">timestamp</option>
								<option value="blob">blob</option>
							</select>
						</td>
						<td>
							<label>DB length</label>
							<input type="text" value="255" class="smallinput dblength" name="dblength1"/>
						</td>
						<td>
							<label>DB default</label>
							<input type="text" class="smallinput dbdefault" name="dbdefault1"/>
						</td>
						<td class="fs10" width="100px">
							<a class="deleteSmall"></a>
							<input type="checkbox" class="required" name="required1" value="1"/>Required<br/>
							<input type="checkbox" class="searchable" name="searchable1" value="1"/>Searchable<br/>
							<input type="checkbox" class="admindisplay" name="admindisplay1" value="1"/>Admin Display<br/>
							<span><input type="checkbox" class="multilanguage" name="multilanguage1" value="1" checked="checked"/>Multi-language</span>
						</td>
					</tr>
					{/if}
				</table>
<br/>
				<a href="javascript:" class="btnGrey addField">Add One More Field</a>
<br/><br/>
			</div>



			<div class="row1">
			<ul class="categories">
				<li>
					<div class="cat">
						<div class="clear"></div>
						<div class="title"><span class="icon"></span>Galleries</div>
						<div><input type="radio" name="gallery" value="1" {if $module->request.gallery==1}checked="checked"{/if}/> Yes &nbsp; <input type="radio" name="gallery" value="0" {if $module->request.gallery==0}checked="checked"{elseif $module->request.gallery!=1}checked="checked"{/if}/> No<div class="right pr20"><input type="checkbox" name="gallery_admindisplay" value="1" {if $module->id&&$module->request.gallery_admindisplay==0}{else}checked="checked"{/if}/>Admin Display</div></div>
						<div class="clear"></div>
					</div>
					<div class="subcats">
						<table class="form">
							<tr>
								<td>Allow multi-galleries:</td>
								<td width="215"><input type="radio" name="multi_galleries" value="1" {if $module->request.multi_galleries==1}checked="checked"{/if}/> Yes &nbsp; <input type="radio" name="multi_galleries" value="0" {if $module->request.multi_galleries!=1}checked="checked"{/if}/> No</td>
								<td><i>last gallery settings below would be used as multi</i></td>
							</tr>
							<tr id="exact_galleries">
								<td>and/or Have exactly:</td>
								<td>
									<select name="galleries_amnt" id="galleries_amnt">
										{assign var=end value=$module->request.galleries_amnt}
										{section name=i start=1 loop=6}
											<option value="{$smarty.section.i.index}" {if $end==$smarty.section.i.index}selected="selected"{/if}>{$smarty.section.i.index}</option>
										{/section}
									</select> &nbsp; galleries</td>
								<td></td>
							</tr>
						</table>
						<div class="galleries">
							{if $end>0}
								{assign var=end value=$end+1}
								{section name=j start=1 loop=$end}
									{assign var=i value=$smarty.section.j.index}
									{assign var=gallery value=$module->galleries[j]}
									<div class="separator"></div>
									<div class="gal">{include file="modules/gallery.frm.tpl"}</div>
								{/section}
							{else}
								<div class="separator"></div>
								{assign var=i value=1}
								<div class="gal">{include file="modules/gallery.frm.tpl"}</div>
							{/if}
						</div>
					</div>
				</li>
			</ul>
			</div>


			<div class="row1">
			<ul class="categories">
				<li>
					<div class="cat">
						<div class="clear"></div>
						<div class="title"><span class="icon"></span>Categories</div>
						<div><input type="radio" name="categories" value="1" {if $module->request.categories==1}checked="checked"{/if}/> Yes &nbsp; <input type="radio" name="categories" value="0" {if $module->request.categories==0}checked="checked"{elseif $module->request.categories!=1}checked="checked"{/if}/> No</div>
						<div class="clear"></div>
					</div>
					<div class="subcats cats">
						<input type="hidden" name="categoriesttl" value="{if $module->request.categoriesttl}{$module->request.categoriesttl}{else}1{/if}"/>
						{assign var=no_cats value=1}
						{section name=c loop=$site->modules}
							{if $site->modules[c].module=='categories'}{assign var=no_cats value=0}{/if}
						{/section}
						{if $no_cats}
						You haven't added the categories module to this site yet.
						{else}
						<span>Select categories table from the list to be associated with this type of objects.</span>
						<table class="form categories">
							{if $module->id&&$module->request.categories==1}
								{section name=j start=1 loop=$module->request.categoriesttl+1}
								<tr>
									<td>Category {$smarty.section.j.index}:</td>
									<td width="100">
										{assign var=index value="category"|cat:$smarty.section.j.index}
										<select name="category{$smarty.section.j.index}">
											<option></option>
											{section name=i loop=$site->modules|@count}
												{if $site->modules[i].module=='categories'}
													{assign var=table value=$site->modules[i].m->db_table|cat:$site->db_prefix}
													<option value="{$table}" {if $table==$module->request[$index]}selected="selected"{/if}>{$site->modules[i].title}</option>
												{/if}
											{/section}
										</select>
									</td>
									{assign var=index value="category"|cat:$smarty.section.j.index}
									{assign var=index value=$index|cat:'_admindisplay'}
									<td><input type="checkbox" value="1" name="category{$smarty.section.j.index}_admindisplay" {if $module->request[$index]==1}checked="checked"{/if} class="admindisplay"/> Admin Display 
									{assign var=index value="category"|cat:$smarty.section.j.index}
									{assign var=index value=$index|cat:'_required'}
									<input type="checkbox" value="1" name="category{$smarty.section.j.index}_required" {if $module->request[$index]==1}checked="checked"{/if} class="required"/> Required</td>
								</tr>
								{/section}
							{else}
							<tr>
								<td>Category 1:</td>
								<td width="100">
									<select name="category1">
										<option></option>
										{section name=i loop=$site->modules|@count}
											{if $site->modules[i].module=='categories'}
												<option value="{$site->db_prefix}{$site->modules[i].m->db_table}">{$site->modules[i].title}</option>
											{/if}
										{/section}
									</select>
								</td>
								<td><input type="checkbox" value="1" name="category1_admindisplay" class="admindisplay"/> Admin Display <input type="checkbox" value="1" name="category1_required" class="required"/> Required</td>
							</tr>
							{/if}
						</table>
						<a href="javascript:" class="btnGrey addCategory">Add One More Category</a><br/><br/>
						{/if}
					</div>
				</li>
			</ul>
			</div>



		</div>

		<div class="submitLine">
			<a href="javascript:" class="btnGreen">{if $module->id}Update{else}Add{/if} Module</a>
		</div>

	</form>
{/if}