<?php


class DealTest extends TestCase
{
    public function testSaveDelete()
    {
        Deal::truncate();
        $d = new Deal();
        $d->name = "asasdasd";
        $d->save();
        $this->assertTrue($d->id != 0);
        $this->assertTrue($d->created != null);
        $this->assertTrue($d->updated != null);
//        $this->assertTrue($rp["deleted"] == 0);

        $d->delete();
        $this->assertTrue($d->deleted == 1);
        Deal::truncate();
    }
} 