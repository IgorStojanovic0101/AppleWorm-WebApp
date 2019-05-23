<div id="pozadina">
    
</div>
<div id="popup">
    <div id="popup_header">
        <label id="obavestenje">
            Obavestenje
        </label>
    </div>
    <div id="popup_body">
        <form method="post" action="admin.php">
            <label id="msg">
                <?php echo $msg;?><br />
            </label>
            <input type="hidden" name="id_elementa" value="<?php echo $id_elementa ?>" />
            <input type="submit" name="<?php echo $btnYes ?>" value="Yes" />
            <input type="submit" name="<?php echo $btnNo ?>" value="No" />
        </form>
    </div>
    <div id="popup_footer">
    </div>
</div>

<!--stil-->
<style>
    #pozadina{
        display:none;
        width:100%;
        height:100%;
        opacity: 0.7;
        position:fixed;
        top:0;
        left:0;
        background-color:lightgray;
    }
    #popup{
        display:none;
        position:fixed;
        background-color: gray;
        width:30%;
        border: 1px solid blue;
        border-radius: 5px;
        padding:0.5%;
    }
    #popup_header{
        background-color: #428bdd;
        padding: 2%;
    }
    #popup_body{
        background-color: white;
        text-align: center;
        padding: 5%;
    }
    #popup_footer{
        background-color: whitesmoke;
        padding: 2%;
        text-align: right;
    }
</style>
<!--skripta-->
<script>
    
    
    (function prikazi(){
        setTimeout(function(){},1000);
        var pozadina = document.getElementById("pozadina");
        var popup = document.getElementById("popup");
        
        pozadina.style.display = "block";
        popup.style.display = "block";
        
        var winwd = window.innerWidth;
        
        /*izracuna se sirina ekrana pri ucitavanju stranice, tako da resize-ovanje nece raditi,
         * ali npr. ako se stranica ucita odma u mobilnom rezimu, radice pravilno tj. pop-up ce biti na sredini*/
        popup.style.left = (winwd/2)-(winwd*30)/200+"px"; 
        popup.style.top = "30%";
    })()
    
</script>