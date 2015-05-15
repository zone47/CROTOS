<?php
/****************************************************************************/

function print_title($title) {
?>
		<h3><?php echo $title; ?></h3>
<?php
}

function print_input_item($variable, $sep_width=30, $input_width=65) {
?><input type="text" ng-model="<?php echo $variable; ?>.text"
			  typeahead="label as label.display for label in suggestWikidata($viewValue, $index)"
   	    typeahead-min-length="1" typeahead-on-select="onSelectLine('<?php echo $variable; ?>', $item)" size="<?php echo $input_width; ?>" /><br ng-if="<?php echo $variable; ?>.wikidata" />
			<small><span class="separator" style="width:<?php echo $sep_width; ?>px;">&nbsp;</span><span ng-if="<?php echo $variable; ?>.wikidata">{{<?php echo $variable; ?>.description}}</span><span ng-if="<?php echo $variable; ?>.wikidata"> &middot;
			<a href="https://www.wikidata.org/wiki/{{<?php echo $variable; ?>.wikidata}}">{{<?php echo $variable; ?>.wikidata}}</a></span></small><?php
}

function print_spacer() {
?>
	<span class="separator">&nbsp;</span>
<?php
}

/****************************************************************************/

function print_label_selector($title, $variable) {
	print_title($title);
?>
    <ul>
			<li ng-repeat="<?php echo $variable ?> in dataModel.<?php echo $variable ?>">
				<?php print_remove($variable); ?>				
				<span style="display:inline-block;width:50px;">
					<select ng-model="<?php echo $variable ?>.lang" ng-change="change()" size="1">
						<option>de</option>
						<option selected>en</option>
						<option>es</option>
						<option>fr</option>
						<option>it</option>
					</select>
				</span>
				<input type="text" ng-model="<?php echo $variable ?>.text" ng-change="change()" size="56" placeholder="" /><br />
			</li>
    </ul>
<?php
	print_add_line($variable);
}

function print_item_selector($title, $variable) {
	print_title($title);
?>
		<ul>
		<li ng-repeat="<?php echo $variable ?> in dataModel.<?php echo $variable ?>">
<?php
	print_input_item($variable);
?>
		</li>
		</ul>
<?php
} // print_item_selector

function print_date_selector($title, $variable) {
	print_title($title);
?>
		<ul>
		<li ng-repeat="date in dataModel.<?php echo $variable ?>">
			<span class="separator">&nbsp;</span><input type="text" ng-model="date.value" ng-change="change()" size="65" /><br />
 			<span class="separator">&nbsp;</span><input type="radio" ng-model="date.precision" ng-change="change()" value="11">Day<br/>
			<span class="separator">&nbsp;</span><input type="radio" ng-model="date.precision" ng-change="change()" value="9">Year<br/>
 			<span class="separator">&nbsp;</span><input type="radio" ng-model="date.precision" ng-change="change()" value="8">Decade<br/>
 			<span class="separator">&nbsp;</span><input type="radio" ng-model="date.precision" ng-change="change()" value="7">Century<br/>
 			<span class="separator">&nbsp;</span><input type="radio" ng-model="date.precision" ng-change="change()" value="6">Millenium<br/>
 		</li>
		</ul>
<?php
} // print_date_selector

function print_gender_selector($title, $variable) {
	print_title($title);
?>
		<ul>
		<li ng-repeat="variable in dataModel.<?php echo $variable ?>">
			<span class="separator">&nbsp;</span><input type="radio" ng-model="variable.option" ng-change="change()" value="male"> Male<br />
 			<span class="separator">&nbsp;</span><input type="radio" ng-model="variable.option" ng-change="change()" value="female"> Female<br/>
 			<span class="separator">&nbsp;</span><span class="separator" style="width:65px"><input type="radio" ng-model="variable.option" ng-change="change()" value="other"> Other </span><?php print_input_item($variable, 100, 55); ?>
		</li>
		</ul>
<?php
} // print_gender_selector

function print_text_selector($title, $variable, $multiple=true) {
	print_title($title);
?>
		<ul>
		<li ng-repeat="<?php echo $variable ?> in dataModel.<?php echo $variable ?>">
<?php
	if ($multiple)
		print_remove($variable);
	else
		print_spacer();
?><input type="text" ng-model="<?php echo $variable ?>.text" ng-change="change()" size="65" placeholder="" /><br />
		</li>
		</ul>
<?php
	if ($multiple)
		print_add_line($variable);
} // print_text_selector

function print_inventory_selector($title, $variable) {
	print_title($title);
?>
		<ul>
		<li ng-repeat="<?php echo $variable ?> in dataModel.<?php echo $variable ?>">
<?php
	print_remove($variable);
?><input type="text" ng-model="<?php echo $variable ?>.text" ng-change="change()" size="65" placeholder="" />
	<input type="checkbox" ng-model="<?php echo $variable ?>.collection" ng-change="change()">link to collection</input><br />
		</li>
		</ul>
<?php
	print_add_line($variable);
} // print_inventory

function print_materials_selector($title, $variable1, $variable2) {
	print_title($title);
?>
		<ul>
			<li ng-repeat="<?php echo $variable1 ?> in dataModel.<?php echo $variable1 ?>">
				<?php print_spacer(); ?><span style="display:inline-block;width:6em;"><input type="checkbox" ng-model="<?php echo $variable1 ?>.value" ng-change="change()">{{<?php echo $variable1 ?>.text}}</input></span>
				<input type="checkbox" ng-model="<?php echo $variable1 ?>.surface" ng-change="change()">painting surface</input><br />
			</li>
		</ul>
		<?php print_spacer(); ?>Other:
		<ul>
		<li ng-repeat="<?php echo $variable2 ?> in dataModel.<?php echo $variable2 ?>">
<?php
	print_remove($variable2);
?><input type="text" ng-model="<?php echo $variable2; ?>.text"
			  typeahead="label as label.display for label in suggestWikidata($viewValue, $index)"
   	    typeahead-min-length="1" typeahead-on-select="onSelectLine('<?php echo $variable2; ?>', $item)" size="65" />
	<input type="checkbox" ng-model="<?php echo $variable2 ?>.surface" ng-change="change()">painting surface</input><br ng-if="<?php echo $variable2; ?>.wikidata" />
	<small><span class="separator" style="width:30px;">&nbsp;</span><span ng-if="<?php echo $variable2; ?>.wikidata">{{<?php echo $variable2; ?>.description}}</span><span ng-if="<?php echo $variable2; ?>.wikidata"> &middot;
	<a href="https://www.wikidata.org/wiki/{{<?php echo $variable2; ?>.wikidata}}">{{<?php echo $variable2; ?>.wikidata}}</a></span></small>
		</li>
		</ul>
<?php
	print_add_line($variable2);
}

function print_instance_selector($title, $variable) {
	print_title($title);
?>
		<ul>
		<li ng-repeat="<?php echo $variable ?> in dataModel.<?php echo $variable ?>">
			<span class="separator">&nbsp;</span><input type="radio" ng-model="<?php echo $variable ?>.option" ng-change="change()" value="drawing"> Drawing<br />
			<span class="separator">&nbsp;</span><input type="radio" ng-model="<?php echo $variable ?>.option" ng-change="change()" value="painting"> Painting<br />
 			<span class="separator">&nbsp;</span><input type="radio" ng-model="<?php echo $variable ?>.option" ng-change="change()" value="sculpture"> Sculpture<br/>
 			<span class="separator">&nbsp;</span><span class="separator" style="width:65px"><input type="radio" ng-model="<?php echo $variable ?>.option" ng-change="change()" value="other"> Other </span><?php print_input_item($variable, 100, 55); ?>
		</li>
		</ul>
<?php
} // print_gender_selector
