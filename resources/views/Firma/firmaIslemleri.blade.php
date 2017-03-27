@extends('layouts.app')
<br>
<br>
 @section('content')
 <style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #fff;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #fff;
}
.div5{
    float:right;
}
.div6{
    float:left;
}
.button {
    background-color: #ccc; /* Green */
    border: none;
    color: white;
    padding: 6px 25px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 8px;
}

</style>

     <div class="container">
         
        @include('layouts.alt_menu') 
             <div class="row">
                 <div class="col-xs-12 col-sm-6 col-md-8">

                     <div class="panel-group">
                         <?php $davetEdilIlanlar = App\BelirlIstekli::where('firma_id',$firma->id)->get(); //davet edilen ilanları buluyoruz
                                
                         ?>
                         <div class="panel panel-default">
                             <div class="panel-heading">Davet Edildiğim İlanlar</div>
                             <div class="panel-body">
                                 <table>
                                     <tr>
                                         <th>İlan İsmi</th>
                                         <th>Başvuru Sayısı</th>
                                         <th></th>
                                     </tr>
                                    @foreach($davetEdilIlanlar as $dvtIlan)
                                    <?php $dIlan = App\Ilan::find($dvtIlan->ilan_id);
                                         $dIlanTeklifsayısı = $dIlan->teklifler()->count();
                                         if(Auth::guest()){
            
                                        }
                                        else{

                                              $kullanici_id = Auth::user()->kullanici_id;

                                              $firmaKont=  App\FirmaKullanici::where( 'kullanici_id', '=', $kullanici_id )
                                                       ->select('firma_id')->get();
                                              $firmaKont=$firmaKont->toArray();

                                              $firma_id=$firmaKont[0]['firma_id'];



                                                $rol_id  = App\FirmaKullanici::where( 'kullanici_id', '=', $kullanici_id)
                                                        ->where( 'firma_id', '=', $firma_id)
                                                        ->select('rol_id')->get();
                                                        $rol_id=$rol_id->toArray();


                                                $querys = App\Rol::join('firma_kullanicilar', 'firma_kullanicilar.rol_id', '=', 'roller.id')
                                                ->where( 'firma_kullanicilar.rol_id', '=', $rol_id[0]['rol_id'])
                                                ->select('roller.adi as rolAdi')->get();
                                                $querys=$querys->toArray();

                                                $rol=$querys[0]['rolAdi'];

                                            }
                                    ?>
                                     <tr>
                                         <td>{{$dIlan->adi}}</td>
                                         <td>{{$dIlanTeklifsayısı}}</td>
                                        @if(Auth::guest())
                                        @else
                                            @if ( $rol === 'Yönetici')
                                                <td><a href="{{ URL::to('teklifGor', array($firma->id,$dIlan->id), false) }}"><button type="button" class="btn btn-primary" name="{{$dIlan->ilan_id}}" id="{{$dIlan->ilan_id}}" style='float:right'>Başvur</button></a><br><br></td>
                                            @elseif ($rol ==='Satış')
                                                <td><a href="{{ URL::to('teklifGor', array($firma->id,$dIlan->id), false) }}"><button type="button" class="btn btn-primary" name="{{$dIlan->ilan_id}}" id="{{$dIlan->ilan_id}}" style='float:right'>Başvur</button></a><br><br></td>
                                            @elseif ($rol ==='Satın Alma / Satış')
                                                <td><a href="{{ URL::to('teklifGor', array($firma->id,$dIlan->id), false) }}"><button type="button" class="btn btn-primary" name="{{$dIlan->ilan_id}}" id="{{$dIlan->ilan_id}}" style='float:right'>Başvur</button></a><br><br></td>
                                            @else
                                            @endif
                                        @endif
                                        
                                     </tr>
                                    @endforeach
                                 </table>

                             </div>
                         </div>
                         <?php $ilanlarFirma = $firma->ilanlar()->
                                orderBy('yayin_tarihi','desc')->limit('5')->get();
                               ?>
                         <div class="panel panel-default">
                             <div class="panel-heading">Son İlanlarım</div>
                             <div class="panel-body">
                                 <table>
                                     <tr>
                                         <th>İlan İsmi</th>
                                         <th>Başvuru Sayısı</th>
                                         <th></th>
                                     </tr>
                                    @foreach($ilanlarFirma as $ilan)
                                    <?php $ilanTeklifsayısı = $ilan->teklifler()->count();
                                         
                                    ?>
                                     <tr>
                                         <td>{{$ilan->adi}}</td>
                                         <td>{{$ilanTeklifsayısı}}</td>
                                         @if($ilan->yayinlanma_tarihi > time())
                                         <td><a href="{{ URL::to('firmaIlanOlustur', array($firma->id,$ilan->id), false) }}"><button class="button"> Düzenle</button></a></td>
                                         @endif
                                     </tr>
                                    @endforeach
                                 </table>

                             </div>
                         </div>

                         <div class="panel panel-default">
                             <div class="panel-heading">Son Başvurularım</div>
                             <div class="panel-body">
                                 <?php $teklifler= DB::table('teklifler')->where('firma_id',$firma->id)
                                         ->limit(5)
                                         ->get(); 
                                        
                                   ?>
                                 <table>
                                     <tr>
                                         <th>Başvuru İlan İsmi</th>
                                         <th>Başvuru Sayısı</th>
                                         <th></th>
                                     </tr>
                                    @foreach($teklifler as $teklif)
                                     <?php 
                                        $ilanlar = App\Ilan::find($teklif->ilan_id);
                                            $ilanTeklifcount= $ilanlar->teklifler()->count();
                                     ?>
                                     <tr>
                                         <td>{{$ilanlar->adi}}</td>
                                         <td>{{$ilanTeklifcount}}</td>
                                         <td><button class="button">Düzenle</button></td>
                                     </tr>
                                    @endforeach 
                                 </table>

                             </div>
                         </div>

                     </div>
                 </div>
                 <div class="col-xs-6 col-md-4">
                     <div class="panel-group">

                         <div class="panel panel-default">
                             <div class="panel-heading"><img src="{{asset('images/istatistik.png')}}">&nbsp;İstatistik</div>
                             <div class="panel-body">
                                 <div>
                                     Toplam İlan Sayım: {{$firma->ilanlar()->count()}}
                                 </div>
                                 <div>
                                      <?php $tekliflerCount= DB::table('teklifler')->where('firma_id',$firma->id)->count(); ?>
                                     Toplam Başvuru Sayım: {{$tekliflerCount}}
                                 </div>
                             </div>
                         </div>
                         <div class="panel panel-default">
                             <div class="panel-heading"><img src="{{asset('images/doluluk.png')}}">&nbsp;Firma Profili Doluluk Oranı</div>
                             <div class="panel-body">
                                 @include('Firma.chart')
                             </div>
                         </div>

                     </div>
                 
                 </div>
             </div>   
    </div>
@endsection
