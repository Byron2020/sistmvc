export interface SincInterface {
  init(): Promise<void>;
  insertBatch(data: any[]): Promise<void>;
  search(text: string): Promise<any[]>;
  count(): Promise<number>;
}
