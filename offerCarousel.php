
<div class="wrapper" style="padding-top:20px;">
<div class="child1">
  <div id="ImageCarouselCSS" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
      <li data-target="#ImageCarouselCSS" data-slide-to="0" class="active"></li>
      <li data-target="#ImageCarouselCSS" data-slide-to="1"></li>
      <li data-target="#ImageCarouselCSS" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
      <div class="carousel-item active">
          <img
              style="width:100%"
              src="Images\Products\Donut_honeydewchocolate.jpg" 
              alt="Honeydew Chocolate"
          />
          <div class="carousel-caption">
          <a style="text-decoration: none"href="productDetails.php?pid=2">
            <h3>Honeydew Chocolate</h3>
            </a>
          </div>
      </div>
      <div class="carousel-item">
          <img
              style="width:100%"
              src="Images\Products\Donut_earlgrey.jpg" 
              alt="Earl Grey"
          />
          <div class="carousel-caption">
          <a style="text-decoration: none"href="productDetails.php?pid=9">
            <h3>Earl Grey</h3>
          </div>
          </a>
      </div>
      <div class="carousel-item">
          <img
              style="width:100%"
              src="Images\Products\Donut_cinnamoncircle.jpg" 
              alt="Cinnamon Circle"
              
          />
          <div class="carousel-caption">
            <a style="text-decoration: none"href="productDetails.php?pid=1">
            <h3>Cinnamon Circle</h3>
            </a>
          </div>
      </div>
    </div>
  </div>
</div>
<div class="child2">
  <div class="image-wrapper">
  <img
    class="side-image"
    src="Images\Products\Donut_matcha.png" 
    alt="Matcha Ring Donut"
  />
  <a href="productDetails.php?pid=8">
  <h3 class="image1" >Try Our New Flavour Here!</h3>
  </a>
  </div>
  <div class="image-wrapper">
  <img
    class="side-image2"
    src="Images\join_now.jpg" 
    alt="Join Donut"
  />
  <a href="register.php">
  <h3 class="image2">Sign Up </br>Now!</h3>
  </a>
  </div>
</div>
</div>


<style>
  .slides{
    display: flex;
    overflow-x:auto;
    scroll-snap-type: x mandatory;
  }
  .slides > div{
    display: flex;
    flex-shrink:0;
    width: 100%;
    height: 300px;
    scroll-snap-align: start;
    scroll-behavior:smooth;
    background: #74ABE2;
    justify-content: center;
    align-items: center;
    font-size:100px;
  }
  .wrapper{
    display:flex;
  }
  .child1{
    width:80%;
    text-align: center;
  }
  .child2{
    height: 100%;
    text-align: center;
  }
  .carousel-inner > .carousel-item > img {
  height:520px;
  object-fit:cover;
  opacity: 0.85;
}

.carousel-caption{
  /* change color code to change caption background color */
  background: #CAF0F8;
  color: #63200D;
}

img{
  border-radius: 3%;
}

.side-image{
  height: 330px;
  margin-left: 20px;
  margin-bottom: 20px;
  width:100%;
  opacity: 0.7;
}
.side-image2{
  height: 170px;
  width:100%;
  margin-left: 20px;
}

.image-wrapper{
  position: relative; 
  text-align: center; 
  color: #63200D;
}

.image1{
  position: absolute; 
  bottom: 1%; 
  left: 50%; 
  transform: translate(-50%, -50%); 
  width: 80%; 
  background: #FCDDEC;
  color: #63200D;
}

.image2{
  position: absolute; 
  bottom: 2%; 
  left: 30%; 
  transform: translate(-50%, -50%); 
  width: 80%;
  color: #63200D;
}

.image2:hover{
  padding-left: 20px;
  font-size: 30px;
}
</style>
