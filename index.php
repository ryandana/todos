<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<div id="successAlert" role="alert" class="alert alert-success fixed top-2 left-1/2 -translate-x-1/2 z-50 w-fit shadow-lg opacity-0 transform translate-y-[-50px] transition-all duration-500">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
  </svg>
  <span>Login Success, now you're directed to the main website!</span>
</div>

<script>
  // Wait for the DOM to fully load
  window.onload = function() {
    // Get the alert element
    var alert = document.getElementById('successAlert');
    
    // Trigger the transition (making the alert visible)
    setTimeout(function() {
      alert.classList.remove('opacity-0', 'translate-y-[-50px]');
      alert.classList.add('opacity-100', 'translate-y-0');
    }, 100); // Delay the transition slightly for smooth effect
    
    // Hide the alert after 5 seconds
    setTimeout(function() {
      alert.classList.remove('opacity-100', 'translate-y-0');
      alert.classList.add('opacity-0', 'translate-y-[-50px]');
    }, 5000); // 5 seconds duration
  };
  setTimeout(function() {
      window.location.href = 'home.php';
    }, 2000);
  window.history.replaceState(null, null, window.location.pathname);
</script>
<?php endif; ?>

<?php if (isset($_GET['failed']) && $_GET['failed'] == 1): ?>
<div id="errorAlert" role="alert" class="alert alert-error fixed top-2 left-1/2 -translate-x-1/2 z-50 w-fit shadow-lg opacity-0 transform translate-y-[-50px] transition-all duration-500">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
  </svg>
  <span>Login Failed!</span>
</div>

<script>
  // Wait for the DOM to fully load
  window.onload = function() {
    // Get the alert element
    var alert = document.getElementById('errorAlert');
    
    // Trigger the transition (making the alert visible)
    setTimeout(function() {
      alert.classList.remove('opacity-0', 'translate-y-[-50px]');
      alert.classList.add('opacity-100', 'translate-y-0');
    }, 100); // Delay the transition slightly for smooth effect
    
    // Hide the alert after 5 seconds
    setTimeout(function() {
      alert.classList.remove('opacity-100', 'translate-y-0');
      alert.classList.add('opacity-0', 'translate-y-[-50px]');
    }, 5000); // 5 seconds duration
  };
  window.history.replaceState(null, null, window.location.pathname);
</script>
<?php endif; ?>

<!DOCTYPE html>
<html data-theme="winter" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="public/output.css">
    <script src="script.js"></script>
</head>

<body class="flex flex-col items-center justify-center h-screen bg-base-300 ">
    <h2 class="text-3xl font-bold mb-4">Login</h2>
    <form action="login_action.php" method="POST">
        <fieldset class="fieldset w-xs bg-base-200 border border-base-300 p-4 rounded-box">

            <label class="fieldset-label">Username</label>
            <label class="input validator">
                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none" stroke="currentColor">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </g>
                </svg>
                <input type="input" required placeholder="Username" name="username" pattern="[A-Za-z][A-Za-z0-9\-]*" minlength="3" maxlength="30" title="Only letters, numbers or dash" />
            </label>
            <p class="validator-hint hidden">
                Must be 3 to 30 characters
                <br />containing only letters, numbers or dash
            </p>

            <label class="fieldset-label">Password</label>
            <label class="input validator">
                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none" stroke="currentColor">
                        <path d="M2.586 17.414A2 2 0 0 0 2 18.828V21a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h.172a2 2 0 0 0 1.414-.586l.814-.814a6.5 6.5 0 1 0-4-4z"></path>
                        <circle cx="16.5" cy="7.5" r=".5" fill="currentColor"></circle>
                    </g>
                </svg>
                <input type="password" name="password" required placeholder="Password" />
            </label>
            
            <button class="btn btn-primary mt-4">Login</button>
            <p class="text-center" href="register.php">Don't have an account? <a class="transition-all duration-600 text-purple-400 hover:text-purple-600" href="register.php">Register</a></p>
        </fieldset>
    </form>
</body>

</html>