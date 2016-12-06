<?PHP

// -------------------------
// REMOVE CARTS
// -------------------------

	$check_cart_exists=$db->query("select id, name, module_location from idevaff_carts");
	while ($qry = $check_cart_exists->fetch()) {
	if (!file_exists($path . '/admin/carts/' . $qry['module_location'])) {
	$st2 = $db->prepare("delete from idevaff_carts where id = ?");
	$st2->execute(array($qry['id']));
	
	$check_cart_enabled = $db->prepare("SELECT COUNT(*) from idevaff_integration where type = ?");
	$check_cart_enabled->execute(array($qry['id']));
	if ($check_cart_enabled->fetchColumn()) {
	$st2 = $db->prepare("delete from idevaff_integration where type = ?");
	$st2->execute(array($qry['id']));
	}
	
	echo "<div class=\"alert alert-danger\"><span style=\"font-size:120%;\">Cart Integration Notice</span><br />A cart has been removed from the integration list: <b>" . $qry['name'] . "</b></div>";
	} }
	

// -------------------------
// ADD NEW CARTS
// -------------------------

	if ($handle = opendir($path . '/admin/carts/')) {
    while (false !== ($entry = readdir($handle))) {
		
		$info = pathinfo($entry);
		
        if ($entry != "." && $entry != ".." && $entry != "module_update.php" && $entry != "notes.php" && $entry != "notes_integration.php" && $info['extension'] == "php") {
		$query = $db->prepare("SELECT COUNT(*) from idevaff_carts where module_location = ?");
		$query->execute(array($entry));
		if (!$query->fetchColumn()) {
		$readingonly = true;
		include($path . '/admin/carts/' . $entry);
		
		$st1 = $db->prepare("insert into idevaff_carts (id, name, cat, module_location,protection_eligible,coupon_code_eligible,per_product_eligible,profile_protection_eligible,recurring_supported,alternate_commission,version) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
		$st1->execute(array($cart_profile,$cart_name,$cart_cat,$entry,$protection_eligible,$coupon_code_eligible,$per_product_eligible,$profile_protection_eligible,$recurring_supported,$alternate_commission_supported,$cart_profile_version));
		
		echo "<div class=\"alert alert-success\"><span style=\"font-size:120%;\">Cart Integration Notice</span><br />A new cart integration was added to your wizard: <b>" . $cart_name . "</b></div>";

		//echo $entry . " was just written.<br />";
		} else { 
		//echo $entry . " already exists.<br />";
		} } }
    closedir($handle);
	}

// -------------------------
// UPDATE EXISTING CARTS
// -------------------------

	if ($handle = opendir($path . '/admin/carts/')) {
    while (false !== ($entry = readdir($handle))) {
		
		$info = pathinfo($entry);
		
        if ($entry != "." && $entry != ".." && $entry != "module_update.php" && $entry != "notes.php" && $entry != "notes_integration.php" && $info['extension'] == "php") {
		$query = $db->prepare("SELECT COUNT(*) from idevaff_carts where module_location = ?");
		$query->execute(array($entry));
		if ($query->fetchColumn()) {
		$readingonly = true;
		include($path . '/admin/carts/' . $entry);
		
		$checkvals = $db->prepare("select id from idevaff_carts where module_location = ? and cat = ? and protection_eligible = ? and coupon_code_eligible = ? and per_product_eligible = ? and profile_protection_eligible = ? and recurring_supported = ? and alternate_commission = ? and version = ?");
		$checkvals->execute(array($entry,$cart_cat,$protection_eligible,$coupon_code_eligible,$per_product_eligible,$profile_protection_eligible, $recurring_supported, $alternate_commission_supported, $cart_profile_version));
		if (!$checkvals->rowCount()) {
			
		$st = $db->prepare("update idevaff_carts set cat = ?, protection_eligible = ?, coupon_code_eligible = ?, per_product_eligible = ?, profile_protection_eligible = ?, recurring_supported = ?, alternate_commission = ?, version = ? where module_location = ?");
		$st->execute(array($cart_cat,$protection_eligible,$coupon_code_eligible,$per_product_eligible,$profile_protection_eligible, $recurring_supported, $alternate_commission_supported, $cart_profile_version, $entry));
		
		echo "<div class=\"alert alert-info\"><span style=\"font-size:120%;\">Cart Integration Notice</span><br />A cart integration had been updated: <b>" . $cart_name . "</b></div>";

		//echo $entry . " was just written.<br />";
		} } else { 
		//echo $entry . " already exists.<br />";
		} } }
    closedir($handle);
	}
	
?>