<?php
//<ul class='breadcrumb'><li><a href='./'>Home</a><span class='divider'>/</span></li><li class='active'>Login</li></ul>
//<h1>Login</h1>
?>
<div class='row'>
	<div class='col-lg-7'>
<form class='form-horizontal' action='' method='post' enctype='multipart/form-data'>
<fieldset>
<legend>Register</legend>

<div class='form-group'>
<label for='name' class='col-lg-4 control-label'>Name</label>
<div class='col-lg-5'><input type='text' class='form-control' name='name' value='<?php echo $last_name; ?>' /></div>
</div>

<div class='form-group'>
<label for='email' class='col-lg-4 control-label'>Email</label>
<div class='col-lg-5'><input type='text' class='form-control' name='email' value='<?php echo $last_email; ?>' /></div>
</div>

<div class='form-group'>
<label for='pass' class='col-lg-4 control-label'>Password</label>
<div class='col-lg-5'><input type='password' class='form-control' name='pass' value='<?php echo $last_pass; ?>'/></div>
</div>

<div class='form-group'>
<label for='pass2' class='col-lg-4 control-label'>Verify password</label>
<div class='col-lg-5'><input type='password' class='form-control' name='pass2' /></div>
</div>

<div class='checkbox'>
  <div class='col-lg-offset-4 col-lg-8'>
    <label><input type='checkbox' name='agree_membership'> I have read and agree to <a href='#'>the membership agreement</a>.</label>
  </div>
</div>

<div class='checkbox'>
  <div class='col-lg-offset-4 col-lg-8'>
    <label><input type='checkbox' name='agree_liability'> I have read and understand the <a href='#'>liability waiver</a>.</label>
  </div>
</div>

<div class='form-group'>
  <div class='col-lg-offset-4 col-lg-5'>
    <button class='btn btn-primary' name='register-now' type='submit'>Register</button>
  </div>
</div>

</fieldset>
</form>
<h4>privacy philosophy</h4>
<ul>
	<li>we limit data collected about you and your use of the platform,</li>
    <li>your personal information is never for sale,</li>
    <li>we use and disclose information to prevent people from abusing the platform, but</li>
    <li>we never disclose it for any other reason unless required by law.</li>
</ul>
</div>
<div class='col-offset-lg-1 col-lg-5'>

<form class='form-horizontal' action='' method='post' enctype='multipart/form-data'>
<fieldset>
<legend>Login</legend>

<div class='form-group'>
<label for='email' class='col-lg-4 control-label'>Email</label>
<div class='col-lg-5'><input type='text' class='form-control' name='email' /></div>
</div>

<div class='form-group'>
<label for='pass' class='col-lg-4 control-label'>Password</label>
<div class='col-lg-5'><input type='password' class='form-control' name='pass' /></div>
</div>

<div class='form-group'>
  <div class='col-lg-offset-4 col-lg-5'>
    <button class='btn btn-primary' name='login-now' type='submit'>Login</button>
  </div>
</div>

</fieldset>
</form>

</div>
</div>