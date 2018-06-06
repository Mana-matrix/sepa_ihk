
    @if(Auth::check())
        <div class="card-body container-fluid">

            <div name="infoBox" class="my-4">
                <div class="row border-bottom border-secondary font-weight-bold">

                    <label class="col-sm-5 col-label"></label>
                    <label class="col-sm-3 col-label text-right">clients</label>
                    <label class="col-sm-4 col-label text-right">fee</label>

                </div>
                <div class="row ">
                    <?php
                    $new_fee=0.00;
                    $new_count=0;
                    if(isset($characters['new'])){
                        foreach ($characters['new']as $client)
                            $new_fee+=$client->xt_memberfee;
                        $new_count=count($characters['new']);
                    }
                    ?>
                    <label class="col-sm-5 col-label">New Clients:</label>
                    <label class="col-sm-3 col-label text-right"><?= $new_count?></label>
                    <label class="col-sm-4 col-label text-right"><?=$new_fee!=0 ? $new_fee : "0.00" ?></label>

                </div>
                <div class="row">
                    <?php
                    $old_fee=0.00;
                    $old_count=0;
                    if(isset($characters['old'])){
                        foreach ($characters['old']as $client)
                            $old_fee+=$client->xt_memberfee;
                        $old_count=count($characters['old']);
                    }
                    ?>
                    <label class="col-sm-5 col-label">Old Clients:</label>
                    <label class="col-sm-3 col-label text-right"><?=$old_count?></label>
                    <label class="col-sm-4 col-label text-right"><?=$old_fee!=0 ? $old_fee : "0.00" ?></label>
                </div>

                <div class="row border-bottom border-secondary">
                    <label class="col-sm-5 col-label">disabled Clients:</label>
                    <label class="col-sm-3 col-label text-right"><?= isset( $characters['disabled'])? count($characters['disabled']):0 ?></label>
                    <label class="col-sm-4 col-label text-right"> -- </label>

                </div>

                <div class="row my-3 border-bottom border-secondary font-weight-bold">
                    <?php
                    $old_fee=0;
                    if(isset($characters['old']))foreach ($characters['old']as $client)
                        $old_fee+=$client->xt_memberfee;
                    ?>
                    <label class="col-sm-5 col-label">paying Clients:</label>
                    <label class="col-sm-3 col-label text-right">= <?=$old_count+$new_count?></label>
                    <label class="col-sm-4 col-label text-right">= <?=$old_fee+$new_fee?></label>
                </div>
            </div>
            <form action="{{ url('/sepa') }}" method="POST" >

                <div class="form-group row ">
                    <label for="first_sepa" class="col-sm-5 my-1 col-form-label text-md-right">First Sepa</label>
                    <div class="col-md-7">
                        <input type="date" name="first_sepa" required="required" autofocus="autofocus" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="follow_sepa" class="col-sm-5 col-form-label text-md-right">Follow Sepa</label>
                    <div class="col-md-7">
                        <input type="date" name="follow_sepa" required="required" autofocus="autofocus" class="form-control">
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-8 my-1 offset-md-3">
                        <button type="submit" class="btn btn-primary">generate new Sepa-xml</button>
                    </div>
                </div>
                <input type="hidden" name="generate" value="true">
                @csrf
                </input>
            </form>





        </div>

    @endif
