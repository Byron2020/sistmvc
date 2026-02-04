import { TestBed } from '@angular/core/testing';

import { SincService } from './sinc-service';

describe('SincService', () => {
  let service: SincService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(SincService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
