<!--mds-->
<center>


# Документ *.mds


-----

<section>
< div .conteiner>
<   div #main header .class1 class2 class3 | data-test="value">

## Многострочный  
- текст с        отступами  
   
<    img #logo .image | src="logo.png" alt="Logo">
<    p >
Обычный текст';


<!-- БЛОК -->
<section>
<1 div .conteiner>
	<2 div #main header .class1 class2 class3 | data-test="value">
	
# Многострочный  
   
- текст с отступами
   
		<3 img #logo .image | src="logo.png" alt="Logo">
		<3 p >Обычный текст
<!--/mds-->

<!-- ЭТО html -->
<center>
<!-- mds -->
	<!-- Кнопка-триггер модального окна -->
<button .btn btn-primary|type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Запустите демо модального окна
  
	<!-- Модальное окно -->
<div #exampleModal .modal fade | tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<1 div .modal-dialog><2 div .modal-content>
<3 div .modal-header>
	<4 h1 #exampleModalLabel .modal-title fs-5>
Заголовок модального окна
	<4 button .btn-close|type="button" data-bs-dismiss="modal" aria-label="Закрыть">
<3 div .modal-body>
Это проверка модального окна в BOOTSTRAP 5
<3 div .modal-footer>
	<4 button .btn btn-secondary | type="button" data-bs-dismiss="modal">
Закрыть
	<4 button  .btn btn-primary | type="button">
Сохранить изменения

<?php
$nom = '777';
$data = date('d.m.Y');
?>

	<!-- ТАБЛИЦА -->
<section>
<1 table #tab tab3 .table><2 tbody>
<3 thead>
<4 tr><5 th |colspan = "2">
ЗАГОЛОВОК mds

**markdown**

<4 tr><5 th>
НОМЕР
<5 th>
ДАТА

<   tbody>
<    tr>
<     td><?= $nom ?>
<     td><?= $data ?>
	
<!-- ОТСТУП -->
<br>';



<!-- ФОРМА -->				
<form |action="./">
< table>
<  tr>
<   td>
<    span>Логин  
<    input #name name[] .control |type="text" placeholder="Логин...">
<  tr>
<   td>
<    span>Пароль
<    input #name name[] .control |type="search" placeholder="ПАРОЛЬ...">
<  tr>
<   td> 
<    input |type="submit" value="ОТПРАВИТЬ">
<!-- /mds -->
	
<!--ЭТО html-->
</center>



## 🚀 **Пример использования**  
```markdown
# Привет, мир!  
Это **Markdown** → **HTML** без лишних сложностей.  
```

---

### 🔥 **Готово!**  
Теперь ваши `.md`-файлы выглядят как полноценные веб-страницы. Просто, быстро, красиво!  

**▶️ Попробуйте прямо сейчас!**  

--- 

<!--mds-->
<section>
<1 form .row g-3>
  <2 div .col-md-6>
    <3 label  .form-label |for="inputEmail4">
	Эл. адрес
    <3 input #inputEmail4 .form-control | type="email" >
  <2 div .col-md-6>
    <3 label  .form-label |for="inputPassword4" >
	Пароль
    <3 input  #inputPassword4 .form-control |type="password">
  <2 div .col-12>
    <3 label .form-label | for="inputAddress" >
	Адрес
    <3 input #inputAddress .form-control | type="text"  placeholder="Проспект Ленина">
  <2 div .col-12>
    <3 label .form-label | for="inputAddress2">
	Адрес 2
    <3 input #inputAddress2 .form-control |  type="text" placeholder="Квартира">
  <2 div .col-md-6>
    <3 label .form-label | for="inputCity" >
	Город
    <3 input  .form-control #inputCity | type="text" placeholder="Брянск">
  <2 div .col-md-4>
    <3 label .form-label | for="inputState" >
	Область
    <3 select #inputState .form-select>
      <4 option |selected>
	  Выберите...
      <4 option>...
  <2 div .col-md-2>
    <3 label .form-label | for="inputZip">
	Индекс
    <3 input #inputZip .form-control | type="text">
  <2 div .col-12>
    <2 div .form-check>
      <3 input #gridCheck .form-check-input | type="checkbox" >
      <3 label .form-check-label | for="gridCheck">
      Проверить меня
  <2 div .col-12>
    <3 button  .btn btn-primary | type="submit">
	Войти в систему
<!--/mds-->
