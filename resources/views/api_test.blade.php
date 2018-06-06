
<label class="row col-sm-12 col-label font-weight-bold">Last transactions:</label>
<table class="table">

    @foreach($characters as $transaction)
        <tr>
            <th class="thead-dark"><?= $transaction->tr_type ?> sepa</th>
            <td><?= $transaction->tr_date ?></td>
            <td>fee</td>
        </tr>
    @endforeach
</table>