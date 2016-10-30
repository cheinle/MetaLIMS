<?php
//CSS:
?>

<!--Datatables-->
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.css">

<!--Latest complied and minified CSS -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
		
<!--jQuery CSS--> 
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css">
		
<!--FontAwesome-->
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

<!--js css-->
<?php echo '<link rel="stylesheet" type="text/css" href="'.$_SESSION['link_root'].'config/jquery.ptTimeSelect.css"></script>'; ?>

<!--<link rel="stylesheet" type="text/css" href="/series/dynamic/am_production/config/jquery.ptTimeSelect.css" />-->




<style>
/* note: class=hidden for hidden text input is not actually defined...*/

/***********Navigation Bar ********/
.navbar{
    border: none;
    border-radius: 2;
    background: linear-gradient(rgba(192,213,219,1),rgba(192,213,219,0.5),rgba(192,213,219,1));
}

.nav.navbar-nav li a {
    color: black;
    font-family: Arial;
  	font-size: 15px;
}

.nav.navbar-nav li a:hover {
    color: black;
    text-shadow: 0px 4px 3px rgba(0,0,0,0.4),
             0px 8px 13px rgba(0,0,0,0.1),
             0px 18px 23px rgba(0,0,0,0.1);
}


.dropdown-submenu {
    position: relative;
}

.dropdown-submenu>.dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -6px;
    margin-left: -1px;
    -webkit-border-radius: 0 6px 6px 6px;
    -moz-border-radius: 0 6px 6px;
    border-radius: 0 6px 6px 6px;
}

.dropdown-submenu:hover>.dropdown-menu {
    display: block;
}

.dropdown-submenu>a:after {
    display: block;
    content: " ";
    float: right;
    width: 0;
    height: 0;
    border-color: transparent;
    border-style: solid;
    border-width: 5px 0 5px 5px;
    border-left-color: #ccc;
    margin-top: 5px;
    margin-right: -10px;
}

.dropdown-submenu:hover>a:after {
    border-left-color: #fff;
}

.dropdown-submenu.pull-left {
    float: none;
}

.dropdown-submenu.pull-left>.dropdown-menu {
    left: -100%;
    margin-left: 10px;
    -webkit-border-radius: 6px 0 6px 6px;
    -moz-border-radius: 6px 0 6px 6px;
    border-radius: 6px 0 6px 6px;
}

/***********Home Page ********/
body.homepage {
   background-image: url('images/dandi.jpg'); 
   background-size:100% 100%;
}
body{
   height: 100%;
   color: black;
   background: #008080;
   font-family: Arial;
}


/***********Page Headers ********/
div.page-header h3 {
    padding-top: 2px;
    padding-bottom: 2px;
    margin-top:2%;
    margin-bottom:2%;
    clear:both;
    color:#666666;
    font-family: Arial;
}

div.page-header{
	 width:90%;
	 margin: 20px 0 20px;
	 margin-left:5%;
	 color:#818181;
	 background: #f1f1f1;
	 border: 2px solid #ccc;
	 padding:10px;
	 font-family: Arial;
	 font-size: 14px;
	 border-radius: 15px;
   	 box-shadow:0 5px 5px rgba(0,0,0,0.5);
	
}

div.vert-checkboxes{
	  float:left;
	  margin: 0px 0px 0px 200px;
}



/***********For Main Tables********/
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
	padding-left: 5px;
	
}
td{
	border: 2px solid black;
	padding-left: 5px;
}
table {
  table-layout:fixed;
  overflow:hidden;
  word-wrap:break-word;
  border: 1px solid black;
  border-radius: 15px;
  box-shadow:0 5px 5px rgba(0,0,0,0.5);
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

/***********For Show/Hide Buttons ********/
button.buttonLength {
     #border: 1px solid black;
	 clear: left;
	 width:100%;
	 float:left;
	 color: black;
	 background: #D8D8D8;
	 padding:10px;
	 margin: 1px 1px 1px 1px;
	 font-family: Arial;
	 font-size: 20px;
	 border-radius: 15px;
   	 box-shadow:0 5px 5px rgba(0,0,0,0.5);
   	 background: linear-gradient(rgba(216,216,216,1),rgba(216,216,216,0.5),rgba(216,216,216,1));
	
}

button.med {
    #border: 1px solid black;
	clear: left;
	width:40%;
	float:left;
	color: black;
	background: #D8D8D8;
	padding:10px;
	margin: 1px 1px 1px 1px;
	font-family: Arial;
	font-size: 20px;
	border-radius: 15px;
   	box-shadow:0 5px 5px rgba(0,0,0,0.5);
   	background: linear-gradient(rgba(216,216,216,1),rgba(216,216,216,0.75),rgba(216,216,216,1));
	
}

button.small-button{
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
	background: linear-gradient(rgba(192,213,219,1),rgba(192,213,219,0.75),rgba(192,213,219,1));
}

/***********For Main Form********/
form.registration{
 width:90%;
 float:left;
 color:#818181;
 background: #f1f1f1;
 border: 2px solid #ccc;
 padding:10px;
 margin-left: 5%;
 font-family: Arial;
 font-size: 14px;
 box-shadow: 0 1px 3px rgba(0,0,0,0.5);
 border-radius: 15px;

}

form.registration fieldset{
  /*border-top:1px solid #ccc;*/
  border-left:0;
  border-bottom:0;
  border-right:0;
  padding:6px;
  margin:0px 0px 0px 0px;
  
}

form.registration legend{
  text-align:left;
  color: #7a7a7a;
  font-size:18px;
  padding:10px 4px 0px 4px;
  margin-left:10px;
  width: 90%;
}

form.registration label.textbox-label,label.password-label{
  font-size: 16px;
  width:200px;
  float: left;
  text-align: right;
  color:#666666;
  clear:left;
  margin:4px 4px 0px 0px;
  padding:0px;
}

form.registration label.textbox-label-sampler{
  font-size: 16px;
  width:200px;
  float: left;
  text-align: right;
  clear:left;
  margin:4px 4px 0px 0px;
  padding:0px;
  color:#52bab3;
}

form.registration h3.checkbox-header-sampler{
  font-size: 16px;
  width:200px;
  text-align: right;
  color:green;
  clear:both;
  margin:8px 8px 0px 0px;
  padding:8px;
  font-family: Arial;
  font-weight: bold;
  color:#52bab3;
  
}

form.registration input[type=text],input[type=password],input[type=email]{
  font-family: Arial;
  font-size: 20px;
  float:left;
  width: 50%;
  border:1px solid #cccccc;
  margin:2px 0px 10px 10px;
  color:#00abdf;
  height:32px;
  padding:3px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.5);
  border-radius: 5px;
}

form.registration textarea.form-control{
  font-family: Arial;
  font-size: 20px;
  float:left;
  width:80%;
  border:1px solid #cccccc;
  margin:2px 0px 10px 10px;
  color:#00abdf;
  height:20%;
  padding:3px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.5);
  border-radius: 5px;
}


form.registration input[type=text].shrtfields,input[type=text].time_fields{
  font-family: Arial;
  font-size: 20px;
  float:left;
  clear: right;
  width: 24.5%;
  border:1px solid #cccccc;
  margin:0px 0px 10px 10px;
  color:#00abdf;
  height:32px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.5);
  border-radius: 5px;
}

form.registration input[type=text].bulkfields{
  font-family: Arial;
  font-size: 20px;
  float: right;
  clear: both;
  width:100%;
  border:1px solid #cccccc;
  color:#00abdf;
  height:32px;
  padding: 0px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.5);
  border-radius: 5px;
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
	color:#666666;

}

form.registration input[type=checkbox],input[type=radio]{
  font-family: Arial;
  float:left;
  width:20px;
  margin:2px 0px 2px 2px;
  color:#00abdf;
  height:16px;
  padding:3px;
}

form.registration label.sm-checkbox{
  position:relative;
  vertical-align: middle;
  width:25%;
  height:2%;
  font-family:Arial;
  height:auto;
  font-family:Georgia;
  font-size:14px;
  display:inline-block;/*inline-flex ?*/
  word-wrap:break-word; 
  overflow:hidden; 
  color:#666666;
}

form.registration h3.checkbox-header{
  font-size: 16px;
  width:200px;
  text-align: right;
  color:#666666;
  clear:both;
  margin:8px 8px 0px 0px;
  padding:8px;
  font-family: Arial;
  font-weight: bold;
  
}

form.registration input:focus, form.registration select:focus{
  background-color:#E0E6FF;
}

form.registration select{
  font-family: Arial;
  font-size: 20px;
  float:left;
  border:1px solid #cccccc;
  margin:2px 0px 10px 10px;
  color:#00abdf;
  height:32px;
  width: 50%;
  box-shadow: 0 1px 3px rgba(0,0,0,0.5);
  border-radius: 5px;
   
  -moz-appearance: none;//remove dropdown arrow because cannot get the arrow to round
  text-indent: 0.01;
  text-overflow: '';
}

form.registration ul{
	column-break-inside: avoid;
    page-break-inside: avoid;           /* Theoretically FF 20+ */
    break-inside: avoid-column;         /* IE 11 */
    display: inline-block; 
    column-fill: balance;
}

/***********For Submit Buttons********/
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
  border-radius: 10px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.5);
  background: linear-gradient(to bottom, #00abdf 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
}
.button:hover{
   background: linear-gradient(to bottom, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
}


/***********Misc********/
container.checkbox{
	flex-direction: column;
	flex-wrap:wrap;
}

a.add{
    color:#0000CD ;
    float:right;
    clear: both;
    font-family: Arial;
}

select::-ms-expand {
    display: none;
}

.ui-corner-all, .ui-corner-bottom, .ui-corner-right, .ui-corner-br {
    border-radius: 10px;
}


/*.error{
	background-color: red;
}
*/
div.border{
 width:90%;
 float:left;
 color:#818181;
 margin-left: 5%;
 margin-bottom: 1%;
 background: #f1f1f1;
 border: 2px solid #ccc;
 padding:10px;
 font-family: Arial;
 font-size: 14px;
 border-radius: 15px;
 box-shadow:0 5px 5px rgba(0,0,0,0.5);

}
div.indent{
  margin-left:20px;
}
div.boxed{
 float: left;
 width: 50%;
}
div.border legend{
  text-align:left;
  color:#666666;
  font-size:18px;
  padding:10px 4px 0px 4px;
  margin-left:20px;
}

/***********Curved Boarder. Used on FAQ ******/
pre.border{
 width:90%;
 float:left;
 color:#818181;
 margin-left: 5%;
 background: #f1f1f1;
 border: 2px solid #ccc;
 padding:10px;
 font-family: Arial;
 font-size: 14px;
 box-shadow: 0 1px 3px rgba(0,0,0,0.5);
 border-radius: 15px;
}

/***********For Bulk Update Tables ********/
table.bulk{

  width:90%;
  float:left;
  color:#818181;
  background: #f1f1f1;
  border: 2px solid #ccc;
  padding:10px;
  font-family: Arial;
  font-size: 14px;
  margin: 10px;
  border-radius: 15px;
  box-shadow:0 1px 3px rgba(0,0,0,0.5);
}

table.bulk tr:last-child td:first-child {
    -moz-border-radius-bottomleft:15px;
    -webkit-border-bottom-left-radius:15px;
    border-bottom-left-radius:15px
}

table.bulk tr:last-child td:last-child {
    -moz-border-radius-bottomright:15px;
    -webkit-border-bottom-right-radius:15px;
    border-bottom-right-radius:15px
}
table.bulk tr:first-child th:first-child {
    -moz-border-radius-bottomleft:15px;
    -webkit-border-top-left-radius:15px;
    border-top-left-radius:15px
}

table.bulk tr:first-child th:last-child {
    -moz-border-radius-bottomright:15px;
    -webkit-border-top-right-radius:15px;
    border-top-right-radius:15px
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
  font-family: Arial;
  font-size: 14px;
  margin: 10px;
  border-radius: 15px;
  box-shadow:0 1px 3px rgba(0,0,0,0.5);
}

/***********For Bulk Update Forms ********/
form.bulk{
  font-family: Arial;
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
  font-family: Arial;
  font-size: 20px;
  float:left;
  border:1px solid #cccccc;
  color:#00abdf;
  height:32px;
  border-radius: 5px;
  box-shadow:0 1px 3px rgba(0,0,0,0.5);
  
  -moz-appearance: none;//remove dropdown arrow because cannot get the arrow to round
  text-indent: 0.01;
  text-overflow: '';
}

form.bulk input[type=checkbox],input[type=radio]{
  font-family: Arial;
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
  font-family: Arial;
  font-weight: bold;
  
}
	
/***********Custom Alert Boxes ********/
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




</style>
