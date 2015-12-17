<?php


class RoyaltyTypeTest extends TestCase
{
    public function testSaveDelete()
    {
        RoyaltyType::truncate();
        $rp = new RoyaltyType();
        $rp->royalty_type_name = "testname";
        $rp->save();
        $this->assertTrue($rp->id != 0);
        $this->assertTrue($rp->created != null);
        $this->assertTrue($rp->updated != null);
//        $this->assertTrue($rp["deleted"] == 0);

        $rp->delete();
        $this->assertTrue($rp->deleted == 1);
        RoyaltyType::truncate();
    }
} 