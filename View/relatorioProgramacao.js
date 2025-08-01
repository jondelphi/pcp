async function filtra(){
    const inicio = document.getElementById('diainicial')
    const fim = document.getElementById('diafinal')
    if (!inicio.value || !fim.value) {
    alert("Por favor, selecione ambas as datas.");
    return;
}

if (new Date(fim.value) >= new Date(inicio.value)) {
    await dados(inicio.value,fim.value)
    await produtos_programados(inicio.value,fim.value)
} else {
    //agora vamos chamar a funçao asincrona
    alert("A data final deve ser maior ou igual à data inicial.");
}
}
async function dados(inicio,fim){
document.getElementById('etiquetas').innerHTML=`<div class="container spinner-border text-primary text-center" role="status">
<span class="visually-hidden">Aguardando...</span>
</div>`
const url = `http://10.1.2.251/api/relProgramacaoEtiqueta/${inicio}/${fim}`
const response = await fetch(url)
const dados = await response.json()
console.log(dados)
await cria_consumo(dados['etiquetas'],inicio,fim)
}
async function cria_consumo(dados,inicio,fim){
const intervalo = `${inicio.substr(8,2)}/${inicio.substr(5,2)} a ${fim.substr(8,2)}/${fim.substr(5,2)}`
const tabela = document.createElement('table')
//estlizar a tabela
tabela.className = 'table table-dark table-bordered table-striped'
const tb_head = document.createElement('thead')
//dados do cabeçalho
tb_head.innerHTML = `<tr><th colspan='3' class='text-center'>Consumo Mínimo de Etiquetas em ${intervalo}</th></tr><tr class='text-center'><th>Código</th><th>Modelo</th><th>Quantidade</th></tr>`
tabela.appendChild(tb_head)
//criar o corpo
const corpo = document.createElement('tbody')
//percorrer o array e fazer as linhas
for (let index = 0; index < dados.length; index++) {       
    const linha = document.createElement('tr')
    const codigo = dados[index].codigo
    const modelo = dados[index].modelo
    const quantidade =dados[index].soma
    const unidade = dados[index].unidade
    linha.innerHTML = `<td>${codigo}</td><td>${modelo}</td><td class='text-end'>${quantidade} ${unidade}</td>`
    corpo.appendChild(linha)  
}

tabela.appendChild(corpo)
document.getElementById('etiquetas').innerHTML = ''
document.getElementById('etiquetas').append(tabela)
}

async function produtos_programados(){
    const inicio = document.getElementById('diainicial')
    const fim = document.getElementById('diafinal')
    if (!inicio.value || !fim.value) {
    alert("Por favor, selecione ambas as datas.");
    return;
}else{
    const url = `http://10.1.2.251/api/relProgramacaoProduto/${inicio.value}/${fim.value}`
    console.log(url)
    const response = await fetch(url)
    const dados = await response.json()
    const d = document.getElementById('programacao')
    const tabela = document.createElement('table')
    //estlizar a tabela
    tabela.className = 'table table-dark table-bordered table-striped'
    const tb_head = document.createElement('thead')
    //dados do cabeçalho
    tb_head.innerHTML = `<tr><th colspan='4' class='text-center'>Programação de Produção em ${inicio.value.substr(8,2)}/${inicio.value.substr(5,2)} a ${fim.value.substr(8,2)}/${fim.value.substr(5,2)}</th></tr><tr class='text-center'><th>Código</th><th>Modelo</th><th>Quantidade</th></tr>`
    tabela.appendChild(tb_head)
    //criar o corpo
    const corpo = document.createElement('tbody')
    //percorrer o array e fazer as linhas
    for (let index = 0; index < dados.length; index++) {
        const linha = document.createElement('tr')
        const codigo = dados[index].codigo
        const modelo = dados[index].modelo
        const quantidade =dados[index].total
        linha.innerHTML = `<td>${codigo}</td><td>${modelo}</td><td class='text-end'>${quantidade}</td>`
        corpo.appendChild(linha)
    }
    tabela.appendChild(corpo)
    d.innerHTML = ''
    d.append(tabela)
}

}