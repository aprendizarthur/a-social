<?php 
//ARQUIVO COM FUNÇÕES PARA INJETAR NO HTML UI PADRÃO 

function barraFerramentas(){
    echo '
    <header>
        <nav id="nav-ferramentas">
            <ul>
                <li>
                    <a class="my-2" href="home.php"><i class="fa-solid fa-house fa-lg px-2" style="color: #272727;"></i><h2 class="ubuntu-regular d-none d-lg-inline-block"> Home</h2></a>
                </li> 
                <li>
                    <a class="d-lg-none my-2" href="#pesquisa"><i class="fa-solid fa-magnifying-glass fa-lg" style="color: #1e3050;"></i></a>
                </li>
                <li>
                    <a class="my-2" href="perfil.php"><i class="fa-regular fa-user fa-xl px-2" style="color: #272727;"></i><h2 class="ubuntu-regular d-none d-lg-inline-block"> Perfil</h2></a>
                </li>
                <li>
                    <a class="d-lg-none my-2" href="#nova-postagem"><i class="fa-solid fa-plus fa-lg" style="color: #272727;"></i></a>
                </li>
                <li>
                    <a class="my-2" href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket fa-lg px-2" style="color: #272727;"></i><h2 class="ubuntu-regular d-none d-lg-inline-block"> Sair</h2></a>
                </li>
                <li>
                    <a class="d-none d-lg-block mt-3 mb-2 btn btn-primary w-100" href="#nova-postagem"><span class="d-none d-lg-block ubuntu-bold d-none d-lg-inline-block">Postar</span></a>
                </li>
                <li>
                    <a class="d-none d-lg-block mb-2 btn btn-primary w-100" href="#pesquisa"><span class="d-none d-lg-block ubuntu-bold d-none d-lg-inline-block">Explorar</span></a>
                </li>
            </ul>
        </nav>
    </header>
    ';
}

?>