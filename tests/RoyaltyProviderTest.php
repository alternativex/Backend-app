<?php


class RoyaltyProviderTest extends TestCase
{
    public function testSaveDelete()
    {
        RoyaltyProvider::truncate();
        $rp = new RoyaltyProvider();
        $rp->royalty_provider_name = "testname";
        $rp->save();
        $this->assertTrue($rp->id != 0);
        $this->assertTrue($rp->created != null);
        $this->assertTrue($rp->updated != null);
//        $this->assertTrue($rp["deleted"] == 0);

        $rp->delete();
        $this->assertTrue($rp->deleted == 1);
        RoyaltyProvider::truncate();
    }
} 