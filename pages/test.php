<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        ::selection {color:#fff;background:#7E858C;}

::-moz-selection {color:#fff;background:#7E858C;}

body{ 
    font-family: monospace;
  background-color: #1d212b;
}

.card-container{ 
  perspective: 700;
}

.businesscard{
  position: relative;
	height:320px;
	width:427px;
   color: #252B37;
  text-transform: uppercase;
  margin-left:auto;
  margin-right:auto;
  margin-top: 50px;
  transition: all 0.9s ease-in;
  transform-style: preserve-3d;

}

.businesscard:hover{ 
  transform: rotateY(180deg);
}

.front, .back{
  height:320px;
  box-shadow: 0px 0px 43px -10px black;	
	width:427px;
  position: absolute;
  cusor: pointer;
  top: 0;
  left: 0;
  backface-visibility:hidden;
  padding: 10px;
  border-radius: 2px;
}

.front{ 
  background-color: #151823;
  color: #D3D4D9;
      animation: textColor 10s ease infinite;
}

.back{ 
  transform: rotateY(180deg); 
  background-color: #DADADA; 
  color: #2518D8; 
  font-size: 1.2em;
}

hr{
  border: 3px solid #3A869E;
}

h1{ font-size: 3em;}

img{ border-radius: 100px;}

p{ font-size: 1.4em; font-weight: 700;}

.info{
  margin-top: 100px;
  background-color: white;
  margin-left: 10px;
  width: 250px;
  padding: 50px;
  text-align:center;
}

@keyframes textColor {
  0% {
    color: #7e0fff;
  }
  50% {
    color: #0fffc1;
  }
  100% {
    color: #7e0fff;
  }
}
    </style>
</head>
<body>
<div class="card-container">
  <div class="businesscard">
    
    <div class="front">
      <img src="https://s3.amazonaws.com/uifaces/faces/twitter/_everaldo/128.jpg">
      <h1>Michael Gearon</h1>
        <hr/>
      <p>Designer</p>
     </div>
    
     <div class="back">
        <p>Telephone: </p>
        <p>Email: test@gmail.com</p>
        <p>Twitter</p>
        <p>Codepen</p>
      </div>
    
  </div>
</div>

<p class="info">Hover Over the 'Card'</p>
</body>
</html>