<!DOCTYPE html>
<html>
<style type="text/css">
	
select.js-example-basic-single{
	width: 500px;
	height: 40px;
}
</style>
<head>
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
	<title>Learn Select 2</title>
</head>
<body>

<select class="js-example-basic-single" name="state" multiple="multiple">
  <option value="AL">Alabama</option>
  <option value="WY">Wyoming</option>
  <option value="AY">Wnging</option>
  <option value="WB">Wadfaming</option>
  <option value="WC">Wysdgffdng</option>
  <option value="WD">Wyomig</option>
</select>

</body>
</html>

<script type="text/javascript">
	$(document).ready(function() {
    //$('.js-example-basic-single').select2();
    $('.js-example-basic-single').select2({
		  placeholder: 'This is my placeholder',
		  allowClear: true
		});
});
</script>

