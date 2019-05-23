<div id="pozadina">
    
</div>
<div id="popup">
    <?php 
        //var_dump($id_namirnice);
    ?>
    <div id="popup_header">
        <label id="obavestenje">
            Izmena namirnice
        </label>
    </div>
    <div id="popup_body">
    <form id="formaIzmenaNam" method="post" action="admin.php" enctype="multipart/form-data">
        <input type="hidden" name="id" value='<?php echo"$nam->id"?>'/>
        <label>Naziv: </label> <input type="text" name="naziv" value='<?php echo"$nam->naziv"?>'/><br />

        <label>Kategorija: </label>
        <select name="kategorija">
            <option id="zitarice"<?php if($nam->kategorija==="Žitarica")echo"selected" ?>>Žitarica</option>
            <option id="testenina"<?php if($nam->kategorija==="Testenina")echo"selected" ?>>Testenina</option>
            <option id="mesnati"<?php if($nam->kategorija==="Mesnati proizvod")echo"selected" ?>>Mesnati proizvod</option>
            <option id="mlecni"<?php if($nam->kategorija==="Mlečni proizvod")echo"selected" ?>>Mlečni proizvod</option>
            <option id="voce"<?php if($nam->kategorija==="Voće")echo"selected" ?>>Voće</option>
            <option id="povrce"<?php if($nam->kategorija==="Povrće")echo"selected" ?>>Povrće</option>
            <option id="slatkisi"<?php if($nam->kategorija==="Slatkiš")echo"selected" ?>>Slatkiš</option>
            <option id="pice"<?php if($nam->kategorija==="Piće")echo"selected" ?>>Piće</option>
            <option id="jelo"<?php if($nam->kategorija==="Jelo")echo"selected" ?>>Jelo</option>
            <option id="ostalo"<?php if($nam->kategorija==="Ostalo")echo"selected" ?>>Ostalo</option>
        </select>
        <br />

        <label>Kalorije: </label> <input class="broj" type="number" step="0.1" name="kalorije" value="<?php echo"$nam->kalorije"?>"/><br />
        <label>Proteini: </label> <input class="broj" type="number" step="0.1" name="proteini" value="<?php echo $nam->proteini ?>"/><br />
        <label>Masti: </label> <input class="broj" type="number" step="0.1" name="masti" value="<?php echo $nam->masti ?>"/><br />
        <label>Ugljeni hidrati: </label> <input class="broj" type="number" step="0.1" name="ugljeni_hidrati" value="<?php echo $nam->ugljeni_hidrati?>" /><br />

        <label>Slika:</label> <input class="slika" type="file" name="slika" /><br /> <!--PROVERITI KAKO FUNKCIONISE SA SLIKOM-->

        <input type="submit" class="dugme" name="btnSacuvajIzmNam" value="Sacuvaj" />
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
        position:absolute;
        top:0;
        left:0;
        background-color:lightgray;
    }
    #popup{
        display:none;
        position:absolute;
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
    #popup_body .broj{
        width:20%;
    }
    #popup_body input{
        margin:2%;
        border-radius: 5px;
        width:50%;
    }
    #popup_body .slika{
        width:65%;
    }
    #popup_body .dugme{
        margin-top: 10%;
        width:40%;
    }
    #popup_body select{
        width:50%;
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
        popup.style.top = "20%";
    })()
    /* 
       var btnOk = document.getElementById("btnOk");
    
       btnOk.onclick=sakrij;
       function sakrij(){
        var pozadina = document.getElementById("pozadina");
        var popup = document.getElementById("popup");
        
        pozadina.style.display = "none";
        popup.style.display = "none";
    }*/
</script>