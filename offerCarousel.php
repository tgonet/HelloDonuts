
<div class="wrapper">
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
              src="Images\Donut_honeydewchocolate.jpg" 
              alt="Honeydew Chocolate"
          />
          <div class="carousel-caption">
            <h3>Honeydew Chocolate</h3>
          </div>
      </div>
      <div class="carousel-item">
          <img
              src="Images\Donut_mangocheesecake.jpg" 
              alt="Mango Cheesecake"
          />
          <div class="carousel-caption">
            <h3>Mango Cheesecake</h3>
          </div>
      </div>
      <div class="carousel-item">
          <img
              src="Images\Donut_shortcake.jpg" 
              alt="Shortcake"
          />
          <div class="carousel-caption">
            <h3>Shortcake</h3>
          </div>
      </div>
    </div>
  </div>
</div>
<div class="child2">
  <div class="image-wrapper">
  <img
    class="side-image"
    src="Images\Donut_matcha.png" 
    alt="Matcha Ring Donut"
  />
  <h3 class="image1">Try Our New Flavour Here!</h3>
  </div>
  <div class="image-wrapper">
  <img
    class="side-image2"
    src="Images\join_now.jpg" 
    alt="Join Donut"
  />
  <h3 class="image2">Sign Up </br>Now!</h3>
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
  height:500px;
  object-fit:contain;
  opacity: 0.85;
}

.carousel-caption{
  /* change color code to change caption background color */
  background: #CAF0F8;
  color: #63200D;
}

.side-image{
  height: 330px;
  padding-bottom: 20px;
  width:100%;
  opacity: 0.7;
}
.side-image2{
  height: 170px;
  width:100%;
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
  background: #FCDDEC
}

.image2{
  position: absolute; 
  bottom: 15%; 
  left: 25%; 
  transform: translate(-50%, -50%); 
  width: 80%;
}

.image2:hover{
  padding-left: 20px;
  font-size: 30px;
}
</style>
