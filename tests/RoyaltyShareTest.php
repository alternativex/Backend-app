<?php


class RoyaltyShareTest extends TestCase
{
    public function testSaveDelete()
    {
        RoyaltyShare::truncate();
        $rp = new RoyaltyShare();
        $rp->royalty_share_name = "testname";
        $rp->save();
        $this->assertTrue($rp->id != 0);
        $this->assertTrue($rp->created != null);
        $this->assertTrue($rp->updated != null);
//        $this->assertTrue($rp["deleted"] == 0);

        $rp->delete();
        $this->assertTrue($rp->deleted == 1);
        RoyaltyShare::truncate();
    }
} 