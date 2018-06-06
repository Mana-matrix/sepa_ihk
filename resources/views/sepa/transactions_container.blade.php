<?php
$response = Route::dispatch(Request::create("/transactions/$type", 'GET'));
$transactions=$response->getData();
?>
<div name="infoBox border-secondary">
   <strong>{{$titel}}</strong>
    <div class="row border-bottom border-secondary font-weight-bold">
        <label class="col-sm-5 col-label"></label>
        <label class="col-sm-3 col-label text-right">clients</label>
        <label class="col-sm-4 col-label text-right">fee</label>
    </div>
    <div class="row ">
        <label class="col-sm-5 col-label">New Clients:</label>
        <label class="col-sm-3 col-label text-right"><?= $transactions->first->tl_member_transactions_count?></label>
        <label class="col-sm-4 col-label text-right">{{$transactions->first->fee}}</label>
    </div>
    <div class="row border-bottom border-secondary">
        <label class="col-sm-5 col-label">Old Clients:</label>
        <label class="col-sm-3 col-label text-right"><?= $transactions->follow->tl_member_transactions_count?></label>
        <label class="col-sm-4 col-label text-right">{{$transactions->follow->fee}}</label>
    </div>
    <div class="row border-bottom border-secondary font-weight-bold">

        <label class="col-sm-5 col-label">total</label>
        <label class="col-sm-3 col-label text-right">{{$transactions->first->tl_member_transactions_count+$transactions->follow->tl_member_transactions_count}}</label>
        <label class="col-sm-4 col-label text-right">{{$transactions->first->fee+$transactions->follow->fee}}</label>
    </div>

    <!--div class="row">


    </div>

    <div class="row border-bottom border-secondary">
        <label class="col-sm-5 col-label">disabled Clients:</label>
        <label class="col-sm-3 col-label text-right"><?= isset( $characters['disabled'])? count($characters['disabled']):0 ?></label>
        <label class="col-sm-4 col-label text-right"> -- </label>

    </div-->


</div>