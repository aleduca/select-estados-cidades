<?php $this->layout('master', ['title' => $title]) ?>
<h2>Carregamento de estados e cidades dinamicamente</h2>

<!--<form action="">
    <select name="" id="states">
        <option value="">Escolha um estado</option>
    </select>
    <select name="" id="cities">
        <option value="">Escolha uma cidade</option>
    </select>

    <button type="submit" id="btn_submit" disabled>Cadastrar</button>
</form> -->

<form action="" x-data="select()" x-init="getStates">
    <select name="" id="states" @change="getCities" x-model="state">
        <option value="">Escolha um estado</option>
        <template x-for="state in states" :key="state.id">
            <option x-bind:value="state.id" x-text="state.nome"></option>
        </template>
    </select>
    <select name="" id="cities" x-ref="cities" @change="citySelected">
        <option value="">Escolha uma cidade</option>
        <template x-for="city in cities">
            <option x-bind:value="city.id" x-text="city.nome"></option>
        </template>
    </select>

    <button type="submit" id="btn_submit" x-ref="submit" disabled>Cadastrar</button>
</form>