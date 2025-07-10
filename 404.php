<style>
@font-face {
  font-family: 'Cuprum';
  src: url('<?php echo get_template_directory_uri(); ?>/fonts/Cuprum.ttf');
  font-style: normal;
  font-display: swap;
}
@font-face {
  font-family: 'Stetica';
  src: url('<?php echo get_template_directory_uri(); ?>/fonts/Stetica.ttf');
  font-style: normal;
  font-display: swap;
}
    .error__page{
width: 100%;
  height: 100%;
  background: #F8EAD2;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
*{
    margin:0;
}
h1,h4{
    font-family: "Cuprum",sans-serif;
    font-weight: 700;
}
h1{
    font-size: 64px;
    line-height: 72px;
}
h4{
    font-size: 28px;
    line-height: 32px;
}
.error__line{
    width: 665px;
    height: 1px;
    background: #493521;
}
.error__text{
    display: flex;
    flex-direction: column;
    gap:3rem;
    align-items: center;
}
.error__symbols,.error__link{
    font-family: 'Stetica',sans-serif;
    font-size: 16px;
    line-height: 22px;
}
.error__link{
    margin-top: 100px;
    background: #493521;
    color:#F8EAD2;
    text-decoration: none;
    padding: 10px 69px;
    border-radius: 20px;
}
.error__link:hover{
    border: 1px solid #493521;
    background: none;
    color:#493521;
    transition: all .3s ease;
}
</style>
<div class="error__page">
    <div class="error__text">
        <h1>404</h1>
        <h4>Такой страницы не существует</h4>
        <div class="error__line"></div>
        <p class="error__symbols">Попробуйте начать все с начала и поищите что-нибудь другое</p>
    </div>
    <a href="/" class="error__link">Вернуться на главную</a>
</div>