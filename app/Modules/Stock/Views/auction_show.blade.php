@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>حراج سهام: {{ $auction->stock->info ?? 'سهام' }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>تعداد سهام:</strong> {{ number_format($auction->shares_count) }}</p>
                            <p><strong>قیمت پایه:</strong> {{ number_format($auction->base_price) }} ریال</p>
                            <p><strong>نوع حراج:</strong> 
                                @switch($auction->type)
                                    @case('single_winner') تک برنده @break
                                    @case('uniform_price') قیمت یکسان @break
                                    @case('pay_as_bid') پرداخت به قیمت پیشنهادی @break
                                @endswitch
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>شروع:</strong> {{ $auction->start_time->format('Y/m/d H:i') }}</p>
                            <p><strong>پایان:</strong> {{ $auction->ends_at ? $auction->ends_at->format('Y/m/d H:i') : '-' }}</p>
                            <p><strong>وضعیت:</strong> 
                                <span class="badge badge-{{ $auction->status === 'running' ? 'success' : 'secondary' }}">
                                    {{ $auction->status === 'running' ? 'فعال' : 'غیرفعال' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    @if($auction->min_bid || $auction->max_bid)
                        <div class="alert alert-info">
                            <strong>محدوده قیمت:</strong>
                            @if($auction->min_bid) حداقل: {{ number_format($auction->min_bid) }} ریال @endif
                            @if($auction->max_bid) حداکثر: {{ number_format($auction->max_bid) }} ریال @endif
                        </div>
                    @endif
                    
                    @if($auction->info)
                        <div class="alert alert-light">
                            <strong>توضیحات:</strong><br>
                            {{ $auction->info }}
                        </div>
                    @endif
                </div>
            </div>
            
            @if($auction->isActive())
                <div class="card mt-3">
                    <div class="card-header">
                        <h5>ثبت پیشنهاد</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('auction.bid', $auction) }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>قیمت پیشنهادی (ریال)</label>
                                        <input type="number" name="price" class="form-control" 
                                               min="{{ $auction->min_bid ?? 0 }}" 
                                               max="{{ $auction->max_bid ?? '' }}"
                                               required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>تعداد سهام</label>
                                        <input type="number" name="quantity" class="form-control" 
                                               min="1" max="{{ $auction->lot_size }}" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">ثبت پیشنهاد</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>پیشنهادات شما</h5>
                </div>
                <div class="card-body">
                    @if($userBids->count() > 0)
                        @foreach($userBids as $bid)
                            <div class="border-bottom pb-2 mb-2">
                                <p><strong>قیمت:</strong> {{ number_format($bid->price) }} ریال</p>
                                <p><strong>تعداد:</strong> {{ number_format($bid->quantity) }}</p>
                                <p><strong>وضعیت:</strong> 
                                    <span class="badge badge-{{ $bid->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ $bid->status === 'active' ? 'فعال' : 'غیرفعال' }}
                                    </span>
                                </p>
                                <small class="text-muted">{{ $bid->created_at->format('Y/m/d H:i') }}</small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">هنوز پیشنهادی ثبت نکرده‌اید.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
