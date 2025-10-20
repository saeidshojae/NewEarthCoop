@extends('layouts.master')

@section('head-tag')
    <!-- profile assets -->
    <link href="{{ asset('profile-assets/css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    <link href="{{ asset('profile-assets/font/bootstrap-icon/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('profile-assets/plugin/waves/waves.min.css') }}" rel="stylesheet">
    <link href="{{ asset('profile-assets/plugin/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('profile-assets/plugin/timer/timer.css') }}" rel="stylesheet">
    <link href="{{ asset('profile-assets/plugin/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet">
    <link href="{{ asset('profile-assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('profile-assets/css/responsive.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    
        
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
    <script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <link href="{{ asset('assets/css/bank-cart.css') }}" rel="stylesheet">
    
    <style>
        .selectField{
            display: block !important
        }
        .nice-select{
            display: none !important
        }
                .detail ul {
                background-color: #ffe6926b;
    margin: 0;
        box-shadow: inset 0px 0 40px 20px #32423229;
        }
        
        .selectField{
            display: block !important
        }
        .nice-select{
            display: none !important
        }
        

        .rules{
                padding: 2rem;
                display: none
        }
        
                

        .hr-rules{
                display: none
        }
        
        .rules span{
            color: #2e96ea;
            font-size: 1.1rem;
        }
        
        .rules .top{
            color: #2e96ea;
            font-size: 1.3rem;
        }
   
   
           .down-rules{
                padding: 2rem;
                display: none;
                position: fixed;
                width: 50%;
                background-color: #fff;
                z-index: 999999;
                top: 7%;
                right: 25%;
                border-radius: .5rem;

        }
        
        #accountPrice, #accountPorift, #accountName{
            color: #009dff;
        }

        
        .down-rules span{
            color: #3d3d3d;
            line-height: 3rem;
            font-size: 1rem;
        }
        
        .down-rules .top{
            color: #333;
            font-size: 1.3rem;
        }
        
        .half-opa .Inner-wrap{
                background-image: none !important;
                    background-color: #efefef;
        }
        .half-opa .bank-detail h3{
            color: #525252;
        }
        .half-opa .Card-number ul li{
            color: #525252;
        }
        
        .half-opa .Expire h5{
            color: #525252;
        }
        
        .half-opa .Expire h4{
            color: #525252;
        }
        
        .half-opa .Name h3{
            color: #525252;
        }
   
        #back{
            position: fixed;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
            z-index: 9;
            display: none;
        }

        .btns{
            display: flex;
    justify-content: space-between;
        }
        
        .btns p {
            margin : 0;
        }
        
        @media screen and (max-width: 990px) {
          .down-rules {
            top: 0;
            right: 0;
            padding: 1.5rem;
            width: 100%;
          }
          
          .down-rules span{
              line-height: 2.5rem;
          }
          
          .btns {
            flex-wrap: wrap;
          }
          
          .btns p, .btns a, .btns input{
              margin: .4rem;
          }
        }
        
        .detail ul {
                background-color: #c7debc6b;
    margin: 0;
        box-shadow: inset 0px 0 40px 20px #32423229;
        }
    
    </style>
@endsection
@section('content')
<br><br>

<div class="content">
    <div class="container-fluid">

        <!-- start side-menu in mobile -->
        @include('layouts.mobile-nav')


        <!-- end side-menu in mobile -->

        <div class="dashboard-panel">
            <div class="row gy-3">
                @include('layouts.nav-var')

                <div class="col-lg-9">
                    <div class="ui-boxs">
                        <div class="ui-box">
                            <div class="ui-box-item ui-box-white">
                                <div class="box">
                                    <div class="box-titre">
                                        <h3>واریز پس انداز اجباری</h3>
                                        <i class="bi bi-question-circle"></i>
                                    </div>
                
                                    <div class="box-content">
                                        <div class="detail">
                                            <ul>
                                                <li>نام: <span>{{ auth()->user()->first_name }}</span></li>
                                                <li>نام خانوادگی: <span>{{ auth()->user()->last_name }}</span></li>
                                                <li>کد کاربری: <span>{{ auth()->user()->id }}</span></li>
                                                <li>نوع درخواست: <span>ثبت</span></li>
                                                <li>مربوط به حساب: <span>وام (واریز پس انداز اجباری)</span></li>
                                            </ul>
                                        </div>


                                        <form action="{{ route('loan.saving.store') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <a href="{{ route('loan.saving.history') }}">مشاهده تاریخچه واریز پس انداز اجباری</a>

                                            <div class="form-group col-12">
                                                <label for="">موضوع درخواست</label>
                                                <select name="loan_id" id="" class="form-control selectField">
                                                    <option value="">انتخاب کنید</option>
                                                    @foreach ($loans as $loan)
                                                        <option saving-value='{{ $loan->compulsory_savings_value }}' amount='{{ $loan->max_amount }}' max-installment='{{ $loan->max_installments }}' value="{{ $loan->id }}" @if(isset($_GET['id']) && $_GET['id'] == $loan->id) selected @endif   {{ old('loan_id') == $loan->id ? 'selected' : '' }}>{{ $loan->title }}</option>
                                                    @endforeach
                                                </select>
                                                @error('loan_id')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>
                <p class='rules'>
                                                <span class='top'>شرایط  حساب <span id='accountName'></span> انتخاب شده توسط شما به شرح ذیل می باشد: </span><br>
                                                <span> نرخ سود <span id='accountPorift'></span> درصد سالیانه به موجودی سپرده به صورت روزانه می باشد و واریز  و برداشت به این نوع  حساب مجاز می باشد.</span>
                                            </p>
                                            <hr class='hr-rules'>
                
                                            <div class="row">
                                                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                                    <label for="">مبلغ (تومان)</label>
                                                    <input type="text" name="amount" class="form-control" value="{{ old('amount') }}" separate='true' style="text-align: left" id='priceInput'>
                                                    @error('amount')
                                                        <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                
                                                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                                    <label for="">تاریخ</label>
                                                    <input class="normal-example" value="{{ date('Y-m-d H:i:s') }}" name="date" style="width: 100%; padding: .5rem;" id="birth_date"/>
                                                    @error('date')
                                                        <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            
                                            <div class='row bank-card d-none'>
                                                @foreach(\App\Models\SiteBankInformation::all() as $bank)
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 bank-card-item half-opa" style='position: relative;'>
                                                    <input type='radio' name='site_bank_information_id' value='{{ $bank->id }}' style='position: absolute; z-index: 5; width: 100%; height: 100%; right: 0; opacity: 0;' class='bank_infos' cart-number='{{ $bank->cart_number }}' owner='{{ $bank->owner }}'>

                                                  <div class="Base">
                                                    <div class="Inner-wrap" style='background-image: url({{ asset('bank-backgrounds/' . rand(1, 6) . '.svg') }})'>
                                                    <div class='bank-detail'>
                                                        <img src='{{ asset('/images/bank-infos/' . $bank->icon) }}'>
                                                        <h3>{{ $bank->bank }}</h3>
                                                    </div>
                                                      <div class="Card-number">
                                                        <ul>
                                                          <li id="first-li">{{ substr($bank->cart_number, 12, 16) }}</li>
                                                          <li>{{ substr($bank->cart_number, 8, 4) }}</li>
                                                          <li>{{ substr($bank->cart_number, 4, 4) }}</li>
                                                          <li id="last-li">{{ substr($bank->cart_number, 0, 4) }}</li>
                                                        </ul>
                                                      </div>
                                                      
                                                      <div class='Expire'>
                                                        <h4>IR {{ $bank->shabba_number }}</h4>
                                                        <h5>شماره حساب: {{ $bank->account_number }}</h5>
                                                      </div>
                                                      
                                                      <div class='Name'>
                                                        <h3>{{ $bank->owner }}</h3>
                                                      </div>
                                                
                                                    </div>
                                                  </div>
                                                </div>
                                                @endforeach
                                            </div>
        
        
                                           
        
                                            <div class="row">
                                                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                                    <label for="">روش پرداخت</label>
                                                    <select name="payment_type" id="type" class="form-control selectField">
                                                        <option value="0" {{ old('payment_type') == 0 ? 'selected' : '' }}>واریز وجه آنلاین </option>
                                                        <option value="1" {{ old('payment_type') == 1 ? 'selected' : '' }}>واریز بانکی یا کارتی به حساب شرکت </option>
                                                        <option value="2" {{ old('payment_type') == 2 ? 'selected' : '' }}>تحویل نقدی وجه </option>
                                                    </select>
                                                    @error('payment_type')
                                                        <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                
                                                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 d-none img">
                                                    <label for="">مایل به بارگذاری سند هستید؟</label>
                                                    <select name="" id="img" class="form-control selectField">
                                                        <option value="0">خیر </option>
                                                        <option value="1">بله </option>
                                                    </select>
                                                </div>
                                                

                                                <div class="form-group d-none receiver  col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                                    <label for="">به کدام شخص تحویل دادید: </label>

                                                    <select class='form-control selectField allowedPerson' name='authorizedـpersons_id'>
                                                        @foreach(\App\Models\AuthorizedPersons::all() as $person)
                                                            <option value='{{ $person->id }}' >{{ $person->name }}</option>
                                                        @endforeach
                                                    </select>

                                                    @error('authorizedـpersons_id')
                                                        <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                

                                                
                                            </div>

                                            <div class='row'>
                                                
                                                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 {{ old('payment_type') == 0 ? 'd-none' : '' }} img-input">
                                                    <label for="">انتخاب سند واریز</label>
                                                    <input type="file" name="transaction_id" class="form-control">
                                                    @error('transaction_id')
                                                        <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                
                                            </div>
                                            
                                            <script>
                                            document.querySelectorAll('.bank-card-item').forEach(item => {
                                                item.addEventListener('click', (e) => {
                                                    document.querySelectorAll('.bank-card-item').forEach(item => {
                                                        item.classList.add('half-opa')
                                                        item.classList.remove('full-opa')
                                                    })
                                                    item.classList.remove('half-opa')
                                                    item.classList.add('full-opa')
                                                })
                                            }) 
                                            let paymentTypeID = 0
                                                document.querySelector('#type').addEventListener('input', (e) => {
                                                    let paymentType = ''
                                                    if(e.target.value == 1){
                                                        document.querySelector('.img').classList.remove('d-none')
                                                        document.querySelector('.bank-card').classList.remove('d-none')
                                                        document.querySelector('.receiver').classList.add('d-none')
                                                        paymentTypeID = 1

                                                    }else if(e.target.value == 2){
                                                        document.querySelector('.img').classList.add('d-none')
                                                        document.querySelector('.bank-card').classList.add('d-none')
                                                        document.querySelector('.receiver').classList.remove('d-none')
                                                        paymentTypeID = 2
                                                    }else{
                                                        document.querySelector('.img').classList.add('d-none')
                                                        document.querySelector('.bank-card').classList.add('d-none')
                                                        document.querySelector('.receiver').classList.add('d-none')
                                                        paymentTypeID = 0
                                                    }
                                                })
                                                
                                                document.querySelector('#img').addEventListener('input', (e) => {
                                                    if(e.target.value == 1){
                                                        document.querySelector('.img-input').classList.remove('d-none')
                                                    }else{
                                                        document.querySelector('.img-input').classList.add('d-none')
                                                    }
                                                })
                                            </script>
                                            <div class="form-group col-12">
                                                <label for="">توضیحات</label>
                                                <textarea name="description" class="form-control" id="">{{ old('description') }}</textarea>
                                                @error('description')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            
                                            <div class='down-rules'>
                                                <span class='top'>متقاضی محترم {{ auth()->user()->fullName() }}</span><hr>
                                                <span>شما تمایل به افزایش در حساب سپرده <span id='accountName'></span> به مبلغ <span id='accountPrice'></span> تومان  ، در تاریخ {{ verta()->format('d-m-Y') }}  با شرایط  نرخ سود <span id='accountPorift'></span> درصد سالیانه  را  به صورت <span id='paymentType'></span> دارید  و  با انتخاب گزینه موافقم تایید می فرمایید که از شرایط سپرده گذاری اطلاع کامل دارید ، در صورت موافق نبودن گزینه (اصلاح میکنم) یا گزینه (انصراف میدهم) را انتخاب فرمایید .</span>
                                                <hr>
                                                <div class='btns'>
                                                    <input type='submit' value='تایید و ارسال' class='btn btn-success'>
                                                    <p onclick='editRequest()' class='btn btn-primary'>اصلاح میکنم</p>
                                                    <a href='{{ route('deposit.account.index') }}' class='btn btn-danger'>انصراف میدهم</a>
                                                </div>
                                            </div>
                                            
                                            

                                            <button type="button" id='subForm' class="default-btn success-btn">ثبت درخواست <span></span></button>
                                             <button class="default-btn warning-btn"><a href="{{ route('deposit.account.index') }}" style="color: #fff">انصراف میدهم</a> <span></span></button>
                                            
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<br><br>

<script>
    $(document).ready(function () {
        console.log("jQuery version:", $.fn.jquery);
        console.log("TouchSpin موجود هست؟", typeof $.fn.TouchSpin); // این باید "function" باشه

        // ساخت یک input ساده و تست TouchSpin
        $('body').append('<input type="text" id="testSpin" value="10" />');
        
        try {
            $('#testSpin').TouchSpin({
                min: 0,
                max: 100,
                step: 5,
                postfix: 'تومان'
            });
        } catch (e) {
            console.error('خطا در اجرای TouchSpin:', e);
        }
    });
</script>

<script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
    });
    
    document.querySelector('#priceInput').addEventListener('input', (e) => {
        document.querySelector('#accountPrice').innerHTML = e.target.value
    })
    

    
    e = document.querySelector('#account')
    profit = e.options[e.selectedIndex].getAttribute('profit');
    name = e.options[e.selectedIndex].getAttribute('name');

    document.querySelector('#subForm').addEventListener('click', () => { 
        if(profit != null && document.querySelector("#priceInput").value != ''){
            let alloweShow = false
            if(paymentTypeID == 1){
                let cartDetail = document.querySelector('input[type=radio]:checked')
                if(cartDetail == null){
                    alert('ابتدا یک کارت انتخاب کنید')
                }else{
                    paymentType = 'واریز وجه به شماره کارت ' + cartDetail.getAttribute('cart-number') + ' به نام ' + cartDetail.getAttribute('owner') 
                    alloweShow = true;
                }
            }else if(paymentTypeID == 2){
                let se = document.querySelector('.allowedPerson')
                paymentType = 'دریافت وجه نقد به گیرنده با نام ' + se.options[se.selectedIndex].text
                alloweShow = true;
            }else{
                paymentType = 'پرداخت از طریق درگاه پرداخت'
                alloweShow = true;
            }
            
            if(alloweShow){
                document.querySelector('#paymentType').innerHTML = paymentType
                document.querySelector('#back').style='display: block'
                document.querySelector('.rules').style='display: block'
                document.querySelector('.down-rules').style='display: block'   
            }
        }else{
            alert('ابتدا مقادیر خواسته شده را وارد کنید')
        }
    })
    
    
    document.querySelector('#account').addEventListener('input', () => { 
                     document.querySelector('.rules').style='display: block'

    })
    
    
    function editRequest(){
        document.querySelector('#back').style='display: none'
        document.querySelector('.down-rules').style='display: none'
    }
    
    if(profit == null){
        document.querySelector('#account').addEventListener('input', () => {
            e = document.querySelector('#account')
            profit = e.options[e.selectedIndex].getAttribute('profit');
            name = e.options[e.selectedIndex].getAttribute('name');
            if(e.options[e.selectedIndex].value == ''){
                document.querySelector('#accountName').innerHTML = ''
                document.querySelector('#accountProfit').innerHTML = ''
            }else{
                
                document.querySelectorAll('#accountName').forEach(item => {
                    item.innerHTML = name
                })
                document.querySelectorAll('#accountPorift').forEach(item => {
                    item.innerHTML = profit + '%'
                })
            }
        });
    }else{
        if(e.options[e.selectedIndex].value == ''){
                document.querySelector('#accountName').innerHTML = ''
                document.querySelector('#accountProfit').innerHTML = ''
            }else{
                
                document.querySelectorAll('#accountName').forEach(item => {
                    item.innerHTML = name
                })
                document.querySelectorAll('#accountPorift').forEach(item => {
                    item.innerHTML = profit + '%'
                })
            }
    }
    
    
</script>

@endsection