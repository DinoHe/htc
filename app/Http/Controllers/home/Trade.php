<?php
namespace App\Http\Controllers\Home;

use App\Http\Controllers\Base;

class Trade extends Base
{
    public function buy()
    {
        return view('home.trade.buy');
    }

    public function unprocessedOrder()
    {
        return view('home.trade.unprocessedOrder');
    }

    public function orderPreview()
    {
        return view('home.trade.orderPreview');
    }

    public function uploadPayImg()
    {
        $file = $this->request->file('pay_img');
        if (empty($file)) return back()->withErrors(['uploadError'=>'请选择要上传的截图'])->withInput();
        $res = $file->store('public/payImg');
        if (empty($res)){
            return back()->withErrors(['uploadError'=>'上传失败，请稍后重新上传'])->withInput();
        }
        return redirect('home/unprocessedOrder');
    }

    public function record()
    {
        return view('home.trade.record');
    }

    public function tradeCenter()
    {
        return view('home.trade.tradeCenter');
    }
}
