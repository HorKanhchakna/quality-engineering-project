import http from 'k6/http';
import { check, sleep } from 'k6';
import { Trend, Rate } from 'k6/metrics';

const avgLatency = new Trend('avg_latency');
const errorRate = new Rate('error_rate');

export const options = {
  stages: [
    { duration: '10s', target: 1 },
    { duration: '10s', target: 10 },
    { duration: '10s', target: 25 },
    { duration: '10s', target: 50 },
    { duration: '10s', target: 0 },
  ],
  thresholds: {
    avg_latency: ['p(95)<2000'],
    error_rate: ['rate<0.18'],
  },
};

const API_BASE = __ENV.API_BASE || 'http://host.docker.internal:8000/api';

export default function () {
  const res = http.get(`${API_BASE}/articles?limit=10&offset=0`);
  
  const success = check(res, {
    'status is 200': (r) => r.status === 200,
  });
  
  avgLatency.add(res.timings.duration);
  errorRate.add(!success);
  
  sleep(1);
}