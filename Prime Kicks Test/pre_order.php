
<html >
  <head>
    <title>Prime Kicks</title>
  </head>
  <style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
*{
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}
html,body{
  height: 100%;
  width: 100%;
  place-items: center;
  background: #f2f2f2;
  
}

nav {
  position:static;
  background: #1b1b1b;
  width: 100%;
  padding: 5px 0;

}
nav .menu {
  max-width: 1250px;
  margin: auto;
  display: flex;
  align-items:center;
  justify-content: space-between;
  padding: 0 20px;
}
.menu .logo a {
  text-decoration: none;
  color: #fff;
  font-size: 35px;
  font-weight: 600;
}
.menu ul {
  display: inline-flex;
}
.menu ul li {
  list-style: none;
  margin-left: 7px;
}
.menu ul li:first-child {
  margin-left: 0px;
}
.menu ul li a {
  text-decoration: none;
  color: #fff;
  font-size: 18px;
  font-weight: 500;
  padding: 8px 15px;
  border-radius: 5px;
  transition: all 0.3s ease;
}
.menu ul li a:hover {
  background: #fff;
  color: black;
}
footer{
  background-color:  #1b1b1b;
  text-align: center;
  color: #fff;
  padding: 5px 0;
  width: 100%;
  margin-top:550px;
}
  </style>
  <body>
    <!--navagation -->
    <nav>
      <div class="menu">
        <div class="logo">
          <a href="#">Prime Kicks</a>
        </div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="About.php">About us</a></li>
            <li><a href="pre_order.php">Pre-Order</a></li>
            <li><a href="cart.php">Mycart</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="Logout.php">Logout</a></li>
        </ul>
      </div>
    </nav>
  <h2>This Page is Under Maintainance.</h2>
    <footer>
      <p> Privacy Policy | Terms and Conditions</p>       
         <p>@ 2024 Prime Kicks, Inc.</p>
    </footer>
  </body>
</html>