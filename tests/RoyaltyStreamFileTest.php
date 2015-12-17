<?php


class RoyaltyStreamFileTest extends TestCase
{
    public function testSaveDelete()
    {
        RoyaltyStreamFile::truncate();
        $rsf = new RoyaltyStreamFile();
        $rsf->deal_id = 2;
        $rsf->stream_file_name = "123123123";
        $rsf->save();
        $this->assertTrue($rsf->id != 0);
        $this->assertTrue($rsf->created != null);
        $this->assertTrue($rsf->updated != null);
//        $this->assertTrue($rp["deleted"] == 0);

        $rsf->delete();
        $this->assertTrue($rsf->deleted == 1);
        RoyaltyStreamFile::truncate();
    }
} 