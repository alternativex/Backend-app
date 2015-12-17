<?php


class RoyaltyStreamTest extends TestCase
{
    public function testSaveDelete()
    {
        RoyaltyStream::truncate();
        $rs = new RoyaltyStream();
        $rs->stream_file_id = 2;
        $rs->save();
        $this->assertTrue($rs->id != 0);
        $this->assertTrue($rs->created != null);
        $this->assertTrue($rs->updated != null);
//        $this->assertTrue($rp["deleted"] == 0);

        $rs->delete();
        $this->assertTrue($rs->deleted == 1);
        RoyaltyStream::truncate();
    }
} 