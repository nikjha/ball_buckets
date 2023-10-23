def fill_bucket(bucket_volume, ball_volumes):
  ball_counts = {}

  for ball_color, ball_volume in ball_volumes.items():
    ball_count = bucket_volume // ball_volume
    ball_counts[ball_color] = ball_count

  return ball_counts



ball_volumes = {
  "pink": 2.5,
  "red": 2,
  "blue": 1,
  "orange": 0.8,
  "green": 0.5
}

ball_counts = fill_bucket(100, ball_volumes)

for ball_color, ball_count in ball_counts.items():
    print(f"{ball_color}: {ball_count}")
