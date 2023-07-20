SELECT MediaTitle AS "Title", MediaID AS "Media ID", get_media_type(MediaID) AS "Media Format"
  FROM Media
 WHERE MediaSeries = ? and MediaID != ?;