<!DOCTYPE html>
<html lang="it">
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Action Item Emails :</title>
<!--
COLORE INTENSE  #9C010F
COLORE LIGHT #EDE8DA

TESTO LIGHT #3F3D33
TESTO INTENSE #ffffff
-->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<style type="text/css">
  * {
    margin: 0;
    padding: 0;
  }
  body {
    font: 14px/1.5 sans-serif;
    /*background: #ebe6e6;*/
    color: #555;
  }
  a {
    color: #3699ff;
    text-decoration: none;
  }
  .container {
    padding: 2em;
  }
  header {
    text-align: center;
    /*background: #d4393e;*/
    color: #000;
    position: relative;
  }
  header img {
    vertical-align: middle;
    position: absolute;
    top: 50%;
    left: 2em;
    transform: translateY(-50%);
  }
  header h1 {
    font-size: 1.5em;
    margin-top: 0;
  }
  header small {
    display: block;
    font-size: 12px;
    font-weight: normal;
  }
  .btn {
    display: inline-block;
    padding: 10px 25px;
    font-size: 1.2em;
    background: #d4393e;
    color: #fff;
    text-decoration: none;
    margin-bottom: 20px;
  }
  p {
    margin-bottom: 20px;
  }
  footer {
    background: #d4393e;
    color: #fff;
    display: flex;
  }
  footer .container {
    width: 100%;
  }
  footer .one-two {
    display: block;
    width: 50%;
    float: left;
  }
  footer:after {
    content: " ";
    display: block;
    clear: both;
  }

  .btn.hadir.a {
    display: inline-block;
    padding: 10px 25px;
    font-size: 1.2em;
    background: #21bf73;
    color: #fff;
    text-decoration: none;
    margin-bottom: 20px;
  }
  .separator {
      display: block;
      height: 0;
      border-bottom: 1px solid #eff2f5;
  }
  .my-10 {
      margin-top: 2.5rem!important;
      margin-bottom: 2.5rem!important;
  }.border-danger {
      border-color: #f1416c!important;
  }
</style>
</head>
<body>
  <header>
    <div class="container">
      <img src="https://www.bankjatim.co.id/themes/bjtm07/assets/img/logo.png" alt="logo" style="max-height: 80px!important;">
      <h1>PT. Bank Jatim
        <small>Undangan Rapat</small>
      </h1>
    </div>
  </header>
  <section>
  <div class="separator border-danger my-10"></div>
    <div class="container">
      <p>{!! $record->deskripsi !!}</p>
      <table width="80%">
        <tbody>
          <tr>
            <td width="200px"><b>Tanggal</b></td>
            <td> : {{ Carbon\Carbon::parse($record->tanggal)->format('d/m/Y') }}</td>
          </tr>
          <tr>
            <td width="200px"><b>Waktu</b></td>
            <td> : {{ Carbon\Carbon::parse($record->waktu_mulai)->format('H:i:s') }} s.d {{ Carbon\Carbon::parse($record->waktu_selesai)->format('H:i:s') }}</td>
          </tr>
          <tr>
            <td width="200px"><b>Tempat</b></td>
            <td> : {{ ucwords($record->tempat) }}</td>
          </tr>
          @if (!is_null($record->link))
          <tr>
            <td width="200px"><b>Link</b></td>
            <td> : <a href="{{ $record->link }}">{{ $record->link }}</a></td>
          </tr>
          @endif
        </tbody>
      </table><br><br>
    </div>
  </section>
  <footer>
    <div class="container">
      <div class="one-two" style="text-align: left">
        PT. Bank Jatim
      </div>
      <div class="one-two" style="text-align: right">
        <span>Jl. Basuki Rahmat No.98-104,<br>&emsp;&emsp;&emsp; Jawa Timur, Indonesia</span><br>
        <span>(031) 531 0090 - 99</span><br>
      </div>
    </div>
  </footer>
</body>
</html>






