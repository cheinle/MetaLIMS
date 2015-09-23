<?php
//CSS:
?>



<!--Latest complied and minified CSS -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
		
<!--Optional theme-->
<!--<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap-theme.min.css">-->
		
<!--jQuery CSS--> 
<!--<link rel="stylesheet" href="//code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui-custom.css">-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css">
		
<!--FontAwesome-->
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

<!--js css-->
<link rel="stylesheet" type="text/css" href="/series/dynamic/airmicrobiomes/config/jquery.ptTimeSelect.css" />


<style>
/* note: class=hidden for hidden text input is not actually defined...*/

.navbar{
    border: none;
    border-radius: 2;
    background: linear-gradient(rgba(192,213,219,1),rgba(192,213,219,0.5),rgba(192,213,219,1));
}

.nav.navbar-nav li a {
    color: black;
    font-family: Georgia;
  	font-size: 16px;
}

.nav.navbar-nav li a:hover {
    color: black;
    text-shadow: 0px 4px 3px rgba(0,0,0,0.4),
             0px 8px 13px rgba(0,0,0,0.1),
             0px 18px 23px rgba(0,0,0,0.1);
}

body.homepage {
   background-image: url('dandi.jpg'); 
   background-size:100% 100%;
}
body{
   height: 100%;
   color: black;
   /*background: grey;*/
   background: #f1f1f1;
   font-family: Georgia;
}

div.page-header h3 {
    padding-top: 2px;
    padding-bottom: 2px;
    margin-top:2%;
    margin-bottom:2%;
    clear:both;
}

div.page-header{
	 width:90%;
	 margin: 20px 0 20px;
	 margin-left:5%;
	 color:#818181;
	 background: #f1f1f1;
	 border: 2px solid #ccc;
	 padding:10px;
	 font-family: Georgia;
	 font-size: 14px;
	 border-radius: 15px;
   	 box-shadow:0 5px 5px rgba(0,0,0,0.5);
	
}

div.vert-checkboxes{
	  float:left;
	  margin: 0px 0px 0px 200px;
}

/**********************************************************************/
/*for bulk tables*/
table.bulk{

  width:90%;
  float:left;
  color:#818181;
  background: #f1f1f1;
  border: 2px solid #ccc;
  padding:10px;
  font-family: Georgia;
  font-size: 14px;
  margin: 10px;
  border-radius: 15px;
  box-shadow:0 1px 3px rgba(0,0,0,0.5);
}
th.bulk{
  font-size: 20px;
  width:100%;
  text-align: center;
  color:#f1f1f1;

}
td.bulk {
    border: 2px solid black; //increase boarder to show up in chrome. Still a little weird looking in firefox
	width: 3.20in;
	white-space: nowrap;
    overflow-x: scroll;
}

table.bulky_bulk{
  float:left;
  color:#818181;
  background: #f1f1f1;
  border: 2px solid #ccc;
  padding:10px;
  font-family: Georgia;
  font-size: 14px;
  margin: 10px;
  border-radius: 15px;
  box-shadow:0 1px 3px rgba(0,0,0,0.5);
}
/**********************************************************************/

/**********************************************************************/
/*for bulk tables*/
form.bulk{
  font-family: Georgia;
  font-size: 20px;
  float:left;
  border:1px solid #cccccc;
  margin:2px 0px 10px 10px;
  color:#00abdf;
  height:32px;
  padding:3px;
  border-radius: 15px;
  box-shadow:0 1px 3px rgba(0,0,0,0.5);
}

form.bulk input[type=text],select{
  font-family: Georgia;
  font-size: 20px;
  float:left;
  border:1px solid #cccccc;
  margin:0px 0px 10px 10px;
  color:#00abdf;
  height:32px;
  border-radius: 15px;
  box-shadow:0 1px 3px rgba(0,0,0,0.5);
}

form.bulk input[type=checkbox],input[type=radio]{
  font-family: Georgia;
  font-size: 20px;
  float:left;
  width:40px;
  
  margin:2px 0px 2px 2px;
  color:#00abdf;
  height:32px;
  padding:3px;
}


form.bulk label.textbox-label,label.password-label{
  font-size: 16px;
  width:200px;
  float: left;
  text-align: right;
  color:#999;
  clear:left;
  margin:4px 4px 0px 0px;
  padding:0px;
}

form.bulk label.checkbox-label,label.radio-label{
	position:relative;
	vertical-align: middle;
	width:200px
	display:inline-flex;
	word-wrap:break-word; 
	overflow:hidden;
	font-size: 14px;
	text-align: left;
}

form.bulk h3.checkbox-header{
  font-size: 16px;
  width:200px;
  text-align: right;
  color:#999;
  clear:both;
  margin:8px 8px 0px 0px;
  padding:8px;
  font-family: Georgia;
  font-weight: bold;
  
}
/**********************************************************************/


p.adjust{
	clear: both;
	margin: 0px;
}
th {
    border: 2px solid black;
    word-wrap:break-word;
	/*white-space : nowrap;*/
	background-color: grey;
	height: .5in;
	
}
table {
  table-layout:fixed;
  overflow:hidden;
  word-wrap:break-word;
  border: 1px solid black;
}
th.reg {
    border: 2px solid black;
    word-wrap:break-word;
	width: 3.20in;
	background-color: grey;
	height: .5in;
	
}
td.reg {
    border: 2px solid black; //increase boarder to show up in chrome. Still a little weird looking in firefox
	width: 3.20in;
	white-space: nowrap;
    overflow-x: scroll;
}
button.buttonLength {
     #border: 1px solid black;
	clear: left;
	
	 width:100%;
	 float:left;
	 /*color:#818181;*/
	 color: black;
	 /*background: #f1f1f1;*/
	 background: #D8D8D8;
	 /*border: 2px solid #ccc;*/
	 padding:10px;
	 margin: 1px 1px 1px 1px;
	 font-family: Georgia;
	 font-size: 20px;
	 border-radius: 15px;
   	 box-shadow:0 5px 5px rgba(0,0,0,0.5);
   	 /*background: linear-gradient(#D8D8D8,grey);*/
   	 background: linear-gradient(rgba(216,216,216,1),rgba(216,216,216,0.5),rgba(216,216,216,1));
	
}

button.med {
    #border: 1px solid black;
	clear: left;
	
	 width:40%;
	 float:left;
	 /*color:#818181;*/
	 color: black;
	 /*background: #f1f1f1;*/
	 background: #D8D8D8;
	 /*border: 2px solid #ccc;*/
	 padding:10px;
	 margin: 1px 1px 1px 1px;
	 font-family: Georgia;
	 font-size: 20px;
	 border-radius: 15px;
   	 box-shadow:0 5px 5px rgba(0,0,0,0.5);
   	 /*background: linear-gradient(#D8D8D8,grey);*/
   	 background: linear-gradient(rgba(216,216,216,1),rgba(216,216,216,0.75),rgba(216,216,216,1));
	
}

button.small-button{
	
	/*color:#818181;*/
	clear: both;
	float:left;
	color: black;
	background: #C0D5DB;
	border-color:#EEEEDBS;
	width: 35%;
	height: 5%;
	margin-left: 2%;
	margin-top: 10px;
	border-radius: 15px;
    box-shadow:0 1px 3px rgba(0,0,0,0.5);
    /*background: linear-gradient(#C0D5DB,#D8D8D8);*/
	background: linear-gradient(rgba(192,213,219,1),rgba(192,213,219,0.75),rgba(192,213,219,1));
}

form.registration ul{
	

	-webkit-column-break-inside: avoid;
    page-break-inside: avoid;           /* Theoretically FF 20+ */
    break-inside: avoid-column;         /* IE 11 */
   display: inline-block; 
   
   -moz-column-fill: balance;
       column-fill: balance;
}


container.checkbox{
	flex-direction: column;
	flex-wrap:wrap;
}

a.add{
    color:#0000CD ;
    float:right;
    clear: both;
    font-family: Georgia;
}

.error{
	background-color: red;
}

div.border{
 width:90%;
 float:left;
 color:#818181;
 background: #f1f1f1;
 border: 2px solid #ccc;
 padding:10px;
 font-family: Georgia;
 font-size: 14px;
 -moz-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
 -webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
 -moz-border-radius: 15px;
 -webkit-border-radius: 15px;
}
div.indent{
  margin-left:20px;
}
div.border legend{
  text-align:left;
  color:#A3A3A3;
  font-size:18px;
  padding:10px 4px 0px 4px;
  margin-left:20px;
}

pre.border{
 width:90%;
 float:left;
 color:#818181;
 margin-left: 5%;
 background: #f1f1f1;
 border: 2px solid #ccc;
 padding:10px;
 font-family: Georgia;
 font-size: 14px;
 -moz-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
 -webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
 -moz-border-radius: 15px;
 -webkit-border-radius: 15px;
}
	
/**********************************************************************/
/*custom alert box css*/
#dialogoverlay{
	display:none;
	opacity: .8;
	position: fixed;
	top:0px;
	left: 0px;
	background: #FFF;
	width: 100%;
	z-index:10;
}
#dialogbox{
	display:none;
	position: fixed; /*will position with javascript*/
	background: #000;
	border-radius:7px;
	width:550px;
	z-index: 10;
}
#dialogbox >div{ background:#FFF; margin:8px;}
#dialogbox > div > #dialogboxhead{background: #666; font-size: 19px; padding:10px; color:#CCC;}
#dialogbox > div > #dialogboxbody{background: #333; padding:20px; color:#FFF;}
#dialogbox > div > #dialogboxfoot{background: #666; padding:10px; text-align:right;}

/* make sidebar nav vertical */ 
@media (min-width: 768px) {
  .sidebar-nav .navbar .navbar-collapse {
    padding: 0;
    max-height: none;
  }
  .sidebar-nav .navbar ul {
    float: none;
  }
  .sidebar-nav .navbar ul:not {
    display: block;
  }
  .sidebar-nav .navbar li {
    float: none;
    display: block;
  }
  .sidebar-nav .navbar li a {
    padding-top: 12px;
    padding-bottom: 12px;
  }
}

.navbar{
	margin-bottom: 0px;
}
/**********************************************************************/


/**********************************************************************/
/*for registration forms*/

form.registration{
 width:90%;
 float:left;
 color:#818181;
 background: #f1f1f1;
 border: 2px solid #ccc;
 padding:10px;
 margin-left: 5%;
 font-family: Georgia;
 font-size: 14px;
 -moz-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
 -webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
 -moz-border-radius: 15px;
 -webkit-border-radius: 15px;
 box-shadow:0 5px 5px rgba(0,0,0,0.5);
}

form.registration fieldset{
  border-top:1px solid #ccc;
  border-left:0;
  border-bottom:0;
  border-right:0;
  padding:6px;
  margin:0px 0px 0px 0px;
  
}

form.registration legend{
  text-align:left;
  /*color:#ccc;*/
  color: #7a7a7a;
  font-size:18px;
  padding:10px 4px 0px 4px;
  margin-left:10px;
}

form.registration label.textbox-label,label.password-label{
  font-size: 16px;
  width:200px;
  float: left;
  text-align: right;
  color:#999;
  clear:left;
  margin:4px 4px 0px 0px;
  padding:0px;
}

form.registration input[type=text],input[type=password],input[type=email]{
  font-family: Georgia;
  font-size: 20px;
  float:left;
  /*width:300px;*/
  width: 50%;
  border:1px solid #cccccc;
  margin:2px 0px 10px 10px;
  color:#00abdf;
  height:32px;
  padding:3px;
  -moz-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
  -webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
}

form.registration textarea.form-control{
  font-family: Georgia;
  font-size: 20px;
  float:left;
  width:80%;
  border:1px solid #cccccc;
  margin:2px 0px 10px 10px;
  color:#00abdf;
  height:20%;
  padding:3px;
  -moz-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
  -webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
}


form.registration input[type=text].shrtfields,input[type=text].time_fields{
  font-family: Georgia;
  font-size: 20px;
  float:left;
  clear: right;
  /*width:145px;*/
  width: 24.5%;
  border:1px solid #cccccc;
  margin:0px 0px 10px 10px;
  color:#00abdf;
  height:32px;
  -moz-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
  -webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
}

form.registration input[type=text].bulkfields{
  font-family: Georgia;
  font-size: 20px;
  float: right;
  clear: both;
  width:100%;
  border:1px solid #cccccc;
  color:#00abdf;
  height:32px;
  padding: 0px;
  -moz-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
  -webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
}




form.registration label.checkbox-label,label.radio-label{
	position:relative;
	vertical-align: middle;
	width:200px
	display:inline-flex;
	word-wrap:break-word; 
	overflow:hidden;
	font-size: 14px;
	text-align: left;

}

form.registration input[type=checkbox],input[type=radio]{
  font-family: Georgia;
  font-size: 20px;
  float:left;
  width:40px;
  
  margin:2px 0px 2px 2px;
  color:#00abdf;
  height:32px;
  padding:3px;
}

form.registration label.sm-checkbox{
  position:relative;
  vertical-align: middle;
  width:25%;
  height:10%;
  font-family:Georgia;
  font-size:14px;
  display:inline-block;/*inline-flex ?*/
  word-wrap:break-word; 
  overflow:hidden; "
}

form.registration h3.checkbox-header{
  font-size: 16px;
  width:200px;
  text-align: right;
  color:#999;
  clear:both;
  margin:8px 8px 0px 0px;
  padding:8px;
  font-family: Georgia;
  font-weight: bold;
  
}


form.registration input:focus, form.registration select:focus{
  background-color:#E0E6FF;
}
form.registration select{
  font-family: Georgia;
  font-size: 20px;
  float:left;
  border:1px solid #cccccc;
  margin:2px 0px 10px 10px;
  color:#00abdf;
  height:32px;
  /*width:300px;*/
  width: 50%;
  -moz-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
  -webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
}

.button, .button:visited{
  float:right;
  clear: left;
  text-shadow: 0 -1px 1px rgba(0,0,0,0.25);
  border-bottom: 1px solid rgba(0,0,0,0.25);
  cursor: pointer;
  padding: 5px 10px 5px 5px;
  color: #fff;
  text-decoration: none;
  font-size: 24px;
  padding: 10px 15px;
  margin:10px 2px 2px 2px;
  background-color: #00abdf;
  display: inline-block;
  -moz-border-radius: 10px;
 -webkit-border-radius: 10px;
 -moz-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
 -webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.5);

  background: linear-gradient(to bottom, #00abdf 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
}
.button:hover{
   /*background-color: #777;*/
   background: linear-gradient(to bottom, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
 
}
/**********************************************************************/

</style>
