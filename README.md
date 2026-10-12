🔮 Descubra seu Signo

Projeto acadêmico desenvolvido para a disciplina de Programação Web.
A aplicação permite informar uma data de nascimento e consultar o signo
do zodíaco correspondente.

✨ Funcionalidades

Formulário para informar a data de nascimento.

Validação da data informada.

Consulta dos signos e suas descrições em um arquivo XML.

Exibição do signo correspondente à data.

Tratamento de erros de consulta e de datas inválidas.

Interface estilizada com CSS e Bootstrap 5.2.

🛠️ Tecnologias utilizadas

PHP

HTML5

CSS3

Bootstrap 5.2

XML

XAMPP (ambiente de desenvolvimento local)

Visual Studio Code

📁 Estrutura do projeto

Project/
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── imgs/
│   └── js/
├── layouts/
│   └── header.php
├── index.php
├── show_zodiac_sign.php
└── signos.xml

▶️ Como executar localmente

Instale e abra o XAMPP.

Inicie o serviço Apache.

Copie a pasta Project para C:\xampp\htdocs\.

Confira se signos.xml está na raiz da pasta Project e contém os
registros dos 12 signos.

Abra o navegador e acesse:

http://localhost/Project/

Informe uma data de nascimento e envie o formulário para consultar o
resultado.

🧩 Como funciona

A página inicial apresenta o formulário. O arquivo
show_zodiac_sign.php recebe a data, valida a entrada e utiliza
simplexml_load_file() para ler os dados de signos.xml. Em seguida,
compara a data com os períodos definidos para os signos e apresenta o
resultado.

🧪 Testes sugeridos

Data dentro do período de Áries.

Data dentro do período de Leão.

Data dentro do período de Peixes.

Data de dezembro correspondente a Capricórnio.

Data de janeiro correspondente a Capricórnio.

Data inválida ou futura.

Execute os testes no navegador e registre no relatório somente os
resultados que você realmente observou.

🌐 Publicação online

Este projeto utiliza PHP, portanto precisa de uma hospedagem que execute
PHP. O GitHub Pages não executa PHP; ele publica arquivos estáticos.
Para disponibilizar a aplicação funcionando, publique os arquivos em uma
hospedagem compatível com PHP, por exemplo, o
InfinityFree.

Depois de criar a conta de hospedagem, envie os arquivos do projeto para
a pasta pública indicada pelo provedor (geralmente htdocs) e teste o
endereço fornecido pela hospedagem.

Observação: o endereço público só poderá ser informado depois que a
hospedagem for criada e os arquivos forem enviados.

👨‍🎓 Finalidade

Projeto desenvolvido para fins acadêmicos e de aprendizagem dos
conceitos de PHP, manipulação de XML, validação de dados e
desenvolvimento web.
